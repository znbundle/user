<?php

namespace ZnBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Yii;
use yii\web\IdentityInterface;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Enums\CredentialTypeEnum;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Libs\Query;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Services\JwtService;

DeprecateHelper::hardThrow();

class AuthService2 extends BaseCrudService implements AuthServiceInterface
{

    protected $passwordService;
    protected $credentialRepository;
    protected $identityRepository;
    protected $jwtService;
    protected $logger;
    protected $identityEntity;

    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        CredentialRepositoryInterface $credentialRepository,
        JwtService $jwtService,
        PasswordService $passwordService,
        LoggerInterface $logger
    )
    {
        $this->identityRepository = $identityRepository;
        $this->passwordService = $passwordService;
        $this->jwtService = $jwtService;
        $this->credentialRepository = $credentialRepository;
        $this->logger = $logger;
    }

    public function getIdentity(): ?IdentityEntityInterface
    {
        return Yii::$app->user->identity;
//        $identityEntity = $this->forgeIdentityEntity(Yii::$app->user->identity);
//        return $identityEntity;
    }

    public function setIdentity(IdentityEntityInterface $identityEntity)
    {
        $this->identityEntity = $identityEntity;
    }

    public function logout()
    {
        Yii::$app->user->logout();
        $this->logger->info('auth logout');
    }

    /*public function authByIdentity(object $identity)
    {

    }*/

    public function authenticationByForm(LoginForm $loginForm)
    {
        try {
            $query = new Query;
            $query->with('roles');
            $userEntity = $this->identityRepository->findUserByUsername($loginForm->login);
        } catch (NotFoundException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth authenticationByForm');
            throw $exception;
        }

        $this->verificationPassword($userEntity, $loginForm->password);
        Yii::$app->user->login($userEntity);
        $this->logger->info('auth authenticationByForm');
    }

    public function authenticationByToken(string $token, string $authenticatorClassName = null)
    {
        $token = explode(' ', $token)[1];
        $jwtEntity = $this->jwtService->verify($token, 'auth');
        $dto = $this->jwtService->decode($token);
        $userId = $dto->payload->subject->id;
        
        $query = new Query;
        $query->with('roles');
        /** @var User $userEntity */
        $userEntity = $this->identityRepository->oneById($userId, $query);
        
        
        
        //dd($userEntity);

        //$userEntity = $this->userManager->findUserByUsername($form->login);
//        if (empty($userEntity)) {
//            throw new NotFoundException();
//            /*$errorCollection = new Collection;
//            $validateErrorEntity = new ValidateErrorEntity;
//            $validateErrorEntity->setField('login');
//            $validateErrorEntity->setMessage('User not found');
//            $errorCollection->add($validateErrorEntity);
//            $exception = new UnprocessibleEntityException;
//            $exception->setErrorCollection($errorCollection);
//            throw $exception;*/
//        }
        //$this->verificationPassword($userEntity, $form->password);
        //$token = $this->forgeToken($userEntity);
        //$token = StringHelper::generateRandomString(64);
        //$userEntity->setApiToken($token);
        $this->logger->info('auth authenticationByToken');
        return $userEntity;
    }

    public function tokenByForm(AuthForm $form): TokenValueEntity
    {
        // @var User $userEntity */
        $userEntity = $this->identityRepository->findUserByUsername($form->getLogin());

        if (empty($userEntity)) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth tokenByForm');
            throw $exception;
        }
        $this->verificationPassword($userEntity, $form->getPassword());
        $token = $this->forgeTokenEntity($userEntity);
        $this->logger->info('auth tokenByForm');
        return $token;
        //$token = StringHelper::generateRandomString(64);
        //$userEntity->setApiToken($token);
        //return $userEntity;
    }

    private function forgeIdentityEntity(IdentityInterface $identity)
    {
        $identityEntity = new IdentityEntity;
        $identityEntity->setId($identity->getId());
        $identityEntity->setLogin($identity->login);
        $identityEntity->setRoles($identity->roles);
        $identityEntity->setStatusId($identity->status);
        //$identityEntity->setUpdatedAt($identity->updated_at);
        $identityEntity->setCreatedAt($identity->created_at);
        return $identityEntity;
    }

    protected function verificationPassword(IdentityEntityInterface $identityEntity, string $password)
    {
        try {
            /** @var CredentialEntity $credentialEntity */
            $credentialEntity = $this->credentialRepository->oneByCredential($identityEntity->getLogin(), CredentialTypeEnum::LOGIN);
            //prr(EntityHelper::toArray($credentialEntity));
            $this->passwordService->validate($password, $credentialEntity->getValidation());
            $this->logger->info('auth verificationPassword');
        } catch (InvalidPasswordException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('password');
            $validateErrorEntity->setMessage('Bad password');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth verificationPassword');
            throw $exception;
        }
    }

    protected function forgeTokenEntity(IdentityEntityInterface $identityEntity): TokenValueEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = ['id' => $identityEntity->getId()];
        $token = $this->jwtService->sign($jwtEntity, 'auth');
        $tokenEntity = new TokenValueEntity;
        $tokenEntity->setIdentity($identityEntity);
        $tokenEntity->setType('jwt');
        $tokenEntity->setToken($token);
        return $tokenEntity;
    }
}
