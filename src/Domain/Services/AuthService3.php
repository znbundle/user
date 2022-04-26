<?php

namespace ZnBundle\User\Domain\Services;

use App\User\Domain\Entities\IdentityEntity;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Url;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Events\IdentityEvent;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Helpers\TokenHelper;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Enums\RegexpPatternEnum;
use ZnCore\Base\Enums\StatusEnum;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Exceptions\NotSupportedException;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Traits\RepositoryAwareTrait;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnUser\Rbac\Domain\Entities\AssignmentEntity;

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
    protected $security;
    protected $em;

    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        CredentialRepositoryInterface $credentialRepository,
        PasswordService $passwordService,
        TokenServiceInterface $tokenService,
        EntityManagerInterface $em,
        Security $security,
        LoggerInterface $logger
    )
    {
        $this->identityRepository = $identityRepository;
        $this->passwordService = $passwordService;
        $this->credentialRepository = $credentialRepository;
        $this->logger = $logger;
        $this->tokenService = $tokenService;
        $this->security = $security;
        $this->em = $em;
        $this->resetAuth();
    }

    protected function resetAuth() {
        $token = new NullToken();
        $this->security->setToken($token);
    }

    public function setIdentity(IdentityEntityInterface $identityEntity)
    {
        if(!$identityEntity->getRoles()) {
            $this->em->loadEntityRelations($identityEntity, ['assignments']);
        }
//        $token = new AnonymousToken([], $identityEntity);
        $token = new UsernamePasswordToken($identityEntity, 'main', $identityEntity->getRoles());
        $this->security->setToken($token);

        //$event = new IdentityEvent($identityEntity);
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::BEFORE_SET_IDENTITY);
        $this->identityEntity = $identityEntity;
        //$this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_SET_IDENTITY);
    }

    public function getIdentity(): ?IdentityEntityInterface
    {
        $identityEntity = null;
        if($this->security->getUser() != null) {
            $identityEntity = $this->security->getUser();
        } /*elseif($this->identityEntity) {
            $identityEntity = $this->identityEntity;
        }*/
        $event = new IdentityEvent();
        $event->setIdentityEntity($identityEntity);
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
        if($this->security->getUser() != null) {
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
        $this->resetAuth();
        $this->logger->info('auth logout');
        $this->getEventDispatcher()->dispatch($event, AuthEventEnum::AFTER_LOGOUT);
    }
    
    public function tokenByForm(AuthForm $loginForm): TokenValueEntity
    {
        $userEntity = $this->getIdentityByForm($loginForm);

        $this->logger->info('auth tokenByForm');
        //$authEvent = new AuthEvent($loginForm);
        $tokenEntity = $this->tokenService->getTokenByIdentity($userEntity);
        $tokenEntity->setIdentity($userEntity);
        $this->em->loadEntityRelations($userEntity, ['assignments']);
        return $tokenEntity;
    }

    public function authByForm(AuthForm $authForm)
    {
        $userEntity = $this->getIdentityByForm($authForm);
        $this->setIdentity($userEntity);
    }
    
    public function authenticationByToken(string $token, string $authenticatorClassName = null)
    {
        $tokenValueEntity = TokenHelper::parseToken($token);
        if($tokenValueEntity->getType() == 'bearer') {
            $userId = $this->tokenService->getIdentityIdByToken($token);
            $query = new Query;
            /** @var User $userEntity */
            $userEntity = $this->identityRepository->oneById($userId, $query);
            $this->logger->info('auth authenticationByToken');
            return $userEntity;
            
        } else {
            throw new NotSupportedException('Token type "' . $tokenValueEntity->getType() . '" not supported in ' . get_class($this));
        }
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
            $errorCollection = ValidationHelper::validateValue($loginForm->getLogin(), [new Email()]);
            $isEmail = $errorCollection->count() <= 0;

            if ($isEmail) {
                $credentialEntity = $this->credentialRepository->oneByCredential($loginForm->getLogin(), 'email');
            } else {
                $credentialEntity = $this->credentialRepository->oneByCredential($loginForm->getLogin(), 'login');
            }
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
