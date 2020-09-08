<?php

namespace ZnBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Services\JwtService;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityIterface;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Base\Domain\Base\BaseCrudService;
use ZnCore\Base\Domain\Entities\ValidateErrorEntity;
use ZnCore\Base\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Base\Domain\Helpers\EntityHelper;
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

    public function getIdentity(): IdentityEntityIterface
    {
        $identityEntity = $this->forgeIdentityEntity(Yii::$app->user->identity);
        return $identityEntity;
    }

    public function authByIdentity(object $identity)
    {

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

    private function verificationPassword(IdentityEntityIterface $identityEntity, string $password): bool
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

    private function forgeTokenEntity(IdentityEntityIterface $identityEntity): TokenEntity
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
