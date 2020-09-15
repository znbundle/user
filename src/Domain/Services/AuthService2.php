<?php

namespace ZnBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use yii2bundle\account\domain\v3\forms\LoginForm;
use yii2bundle\account\domain\v3\helpers\AuthHelper;
use ZnBundle\User\Domain\Entities\User;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Domain\Libs\Query;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Services\JwtService;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use Yii;
use yii\web\IdentityInterface;

class AuthService2 extends BaseCrudService implements AuthServiceInterface
{

    private $passwordService;
    private $securityRepository;
    private $jwtService;

    public function __construct(
        IdentityRepositoryInterface $repository,
        SecurityRepositoryInterface $securityRepository,
        JwtService $jwtService,
        PasswordService $passwordService)
    {
        $this->repository = $repository;
        $this->passwordService = $passwordService;
        $this->jwtService = $jwtService;
        $this->securityRepository = $securityRepository;
    }

    public function getIdentity(): IdentityEntityInterface
    {
        $identityEntity = $this->forgeIdentityEntity(Yii::$app->user->identity);
        return $identityEntity;
    }

    public function logout() {
        Yii::$app->user->logout();
    }

    public function authByIdentity(object $identity)
    {

    }

    public function authenticationByForm(LoginForm $loginForm)
    {
        try {
            $query = new Query;
            $query->with('roles');
            $userEntity = $this->repository->findUserByUsername($loginForm->login);
        } catch (NotFoundException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }

        $this->verificationPassword($userEntity, $loginForm->password);
        Yii::$app->user->login($userEntity);
    }

    public function authenticationByToken(string $token, string $authenticatorClassName = null)
    {
        /** @var User $userEntity */

        $token = explode(' ', $token)[1];

        $jwtEntity = $this->jwtService->verify($token, 'auth');
        $dto = $this->jwtService->decode($token);

        $userId = $dto->payload->subject->id;
        //prr($userId);
        $query = new Query;
        $query->with('roles');
        $userEntity = $this->repository->oneById($userId, $query);
        //dd($userEntity);

        //$userEntity = $this->userManager->findUserByUsername($form->login);
        if (empty($userEntity)) {
            throw new NotFoundException();
            /*$errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;*/
        }
        //$this->verificationPassword($userEntity, $form->password);
        //$token = $this->forgeToken($userEntity);
        //$token = StringHelper::generateRandomString(64);
        //$userEntity->setApiToken($token);
        return $userEntity;
    }

    private function forgeIdentityEntity(IdentityInterface $identity) {
        $identityEntity = new IdentityEntity;
        $identityEntity->setId($identity->getId());
        $identityEntity->setLogin($identity->login);
        $identityEntity->setRoles($identity->roles);
        $identityEntity->setStatus($identity->status);
        //$identityEntity->setUpdatedAt($identity->updated_at);
        $identityEntity->setCreatedAt($identity->created_at);
        return $identityEntity;
    }

    public function tokenByForm(AuthForm $form)
    {
        //prr($form);
        // @var User $userEntity */
        $userEntity = $this->repository->findUserByUsername($form->login);
        //prr($userEntity);
        if (empty($userEntity)) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
        $this->verificationPassword($userEntity, $form->password);
        $token = $this->forgeTokenEntity($userEntity);
        return $token;
        //$token = StringHelper::generateRandomString(64);
        //$userEntity->setApiToken($token);
        //return $userEntity;
    }

    private function verificationPassword(IdentityEntityInterface $identityEntity, string $password): bool
    {
        try {
            $securityEntity = $this->securityRepository->oneByIdentityId($identityEntity->getId());
            //prr(EntityHelper::toArray($securityEntity));
            return $this->passwordService->validate($password, $securityEntity->getPasswordHash());
        } catch (InvalidPasswordException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('password');
            $validateErrorEntity->setMessage('Bad password');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
    }

    private function forgeTokenEntity(IdentityEntityInterface $identityEntity): TokenEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = ['id' => $identityEntity->getId()];
        $token = $this->jwtService->sign($jwtEntity, 'auth');
        $tokenEntity = new TokenEntity;
        $tokenEntity->setIdentity($identityEntity);
        $tokenEntity->setType('jwt');
        $tokenEntity->setToken($token);
        return $tokenEntity;
    }
}
