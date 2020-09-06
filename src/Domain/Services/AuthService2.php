<?php

namespace PhpBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use PhpBundle\Crypt\Domain\Exceptions\InvalidPasswordException;
use PhpBundle\Crypt\Domain\Services\PasswordService;
use PhpBundle\Jwt\Domain\Entities\JwtEntity;
use PhpBundle\Jwt\Domain\Services\JwtService;
use PhpBundle\User\Domain\Entities\IdentityEntity;
use PhpBundle\User\Domain\Interfaces\Entities\IdentityEntityIterface;
use PhpBundle\User\Domain\Entities\TokenEntity;
use PhpBundle\User\Domain\Forms\AuthForm;
use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use PhpBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;
use PhpLab\Core\Domain\Entities\ValidateErrorEntity;
use PhpLab\Core\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Legacy\Yii\Helpers\ArrayHelper;
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
