<?php

namespace ZnBundle\User\Domain\Services;

use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Events\IdentityEvent;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Traits\RepositoryAwareTrait;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;

class AuthService3 implements AuthServiceInterface
{

    use RepositoryAwareTrait;
    use EventDispatcherTrait;
    
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
        //$event = new IdentityEvent($identityEntity);
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_SET_IDENTITY);
        $this->identityEntity = $identityEntity;
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_SET_IDENTITY);
    }

    public function getIdentity(): IdentityEntityInterface
    {
        $event = new IdentityEvent();
        $event->setIdentityEntity($this->identityEntity);
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_GET_IDENTITY);
        /*if($event->getIdentityEntity()) {
            return $event->getIdentityEntity();
        }*/
        if($this->isGuest()) {
            throw new UnauthorizedException();
        }
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_GET_IDENTITY);
        return $event->getIdentityEntity();
    }

    public function isGuest(): bool
    {
        if(is_object($this->identityEntity)) {
            return false;
        }
        $event = new IdentityEvent($this->identityEntity);
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_IS_GUEST);
        if(is_bool($event->getIsGuest())) {
            return $event->getIsGuest();
        }
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_IS_GUEST);
        return true;
    }

    public function logout()
    {
        $event = new IdentityEvent($this->identityEntity);
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_LOGOUT);
        
        $this->identityEntity = null;
        $this->logger->info('auth logout');
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_LOGOUT);
    }

    public function tokenByForm(AuthForm $loginForm): TokenEntity
    {
        $userEntity = $this->getIdentityByForm($loginForm);
        $this->logger->info('auth tokenByForm');
        //$authEvent = new AuthEvent($loginForm);
        return $this->tokenService->getTokenByIdentity($userEntity);
    }
    
    public function authByForm(AuthForm $authForm)
    {
        $userEntity = $this->getIdentityByForm($authForm);
        $this->setIdentity($userEntity);
    }

    public function authenticationByToken(string $token, string $authenticatorClassName = null)
    {
        $userId = $this->tokenService->getIdentityIdByToken($token);
        $query = new Query;
        $query->with('roles');
        /** @var User $userEntity */
        $userEntity = $this->identityRepository->oneById($userId, $query);
       // dd($userEntity);
        $this->logger->info('auth authenticationByToken');
        return $userEntity;
    }

    /*public function authenticationByForm(LoginForm $loginForm)
    {
        DeprecateHelper::softThrow();
        $authForm = new AuthForm([
            'login' => $loginForm->login,
            'password' => $loginForm->password,
            'rememberMe' => $loginForm->rememberMe,
        ]);
        $this->authByForm($authForm);
        $this->logger->info('auth authenticationByForm');
    }*/

    private function getIdentityByForm(AuthForm $loginForm): IdentityEntityInterface {
        ValidationHelper::validateEntity($loginForm);
        $authEvent = new AuthEvent($loginForm);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);
        try {
            $credentialEntity = $this->credentialRepository->oneByCredential($loginForm->getLogin(), 'login');
        } catch (NotFoundException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage(I18Next::t('user', 'auth.user_not_found'));
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth authenticationByForm');
            //$this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_ERROR);
            throw $exception;
        }
        try {
            $this->verificationPasswordByCredential($credentialEntity, $loginForm->getPassword());
        } catch (UnprocessibleEntityException $e) {
            $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_ERROR);
            throw $e;
        }

        $userEntity = $this->identityRepository->oneById($credentialEntity->getIdentityId());
        $authEvent->setIdentityEntity($userEntity);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_SUCCESS);
        return $userEntity;
    }

    protected function verificationPasswordByCredential(CredentialEntity $credentialEntity, string $password)
    {
        try {
            $this->passwordService->validate($password, $credentialEntity->getValidation());
            $this->logger->info('auth verificationPassword');
        } catch (InvalidPasswordException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity('password', I18Next::t('user', 'auth.incorrect_password'));
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            $this->logger->warning('auth verificationPassword');
            throw $exception;
        }
    }
}
