<?php

namespace ZnBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Yii;
use yii\web\IdentityInterface;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Libs\Query;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Services\JwtService;

class AuthService3 extends BaseCrudService implements AuthServiceInterface
{

    protected $tokenService;
    protected $passwordService;
    protected $credentialRepository;
    protected $identityRepository;
    protected $logger;
    protected $identityEntity;

    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        CredentialRepositoryInterface $credentialRepository,
        PasswordService $passwordService,
        TokenServiceInterface $tokenService,
        LoggerInterface $logger
    )
    {
        $this->identityRepository = $identityRepository;
        $this->passwordService = $passwordService;
        $this->credentialRepository = $credentialRepository;
        $this->logger = $logger;
        $this->tokenService = $tokenService;
    }

    public function setIdentity(IdentityEntityInterface $identityEntity)
    {
        $this->identityEntity = $identityEntity;
        Yii::$app->user->login($identityEntity);
    }

    public function getIdentity(): IdentityEntityInterface
    {
        return Yii::$app->user->identity;
//        $identityEntity = $this->forgeIdentityEntity(Yii::$app->user->identity);
//        return $identityEntity;
    }

    public function isGuest(): bool
    {
        return !is_object($this->identityEntity);
    }

    public function logout()
    {
        Yii::$app->user->logout();
        $this->logger->info('auth logout');
    }

    public function tokenByForm(AuthForm $loginForm): TokenEntity
    {
        ValidationHelper::validateEntity($loginForm);

        try {
            $credentialEntity = $this->credentialRepository->oneByCredential($loginForm->getLogin(), 'login');
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
        $this->verificationPasswordByCredential($credentialEntity, $loginForm->getPassword());
        $userEntity = $this->identityRepository->oneById($credentialEntity->getIdentityId());
        //$this->setIdentity($userEntity);

        $token = $this->forgeTokenEntity($userEntity);
        $this->logger->info('auth tokenByForm');
        return $token;
    }

    public function authenticationByForm(LoginForm $loginForm)
    {
        try {
            $credentialEntity = $this->credentialRepository->oneByCredential($loginForm->login, 'login');
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
        $this->verificationPasswordByCredential($credentialEntity, $loginForm->password);
        $userEntity = $this->identityRepository->oneById($credentialEntity->getIdentityId());
        $this->setIdentity($userEntity);
        $this->logger->info('auth authenticationByForm');
    }

    protected function verificationPasswordByCredential(CredentialEntity $credentialEntity, string $password)
    {
        try {
            /** @var CredentialEntity $credentialEntity */
//            $credentialEntity = $this->credentialRepository->oneByCredential($identityEntity->getLogin(), CredentialTypeEnum::LOGIN);
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

    protected function forgeTokenEntity(IdentityEntityInterface $identityEntity): TokenEntity
    {
        return $this->tokenService->getTokenByIdentity($identityEntity);
    }

    public function authenticationByToken(string $token, string $authenticatorClassName = null)
    {
        $userId = $this->tokenService->getIdentityIdByToken($token);

        $query = new Query;
        $query->with('roles');
        /** @var User $userEntity */
        $userEntity = $this->identityRepository->oneById($userId, $query);
        $this->logger->info('auth authenticationByToken');
        return $userEntity;
    }
}
