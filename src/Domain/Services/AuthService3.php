<?php

namespace ZnBundle\User\Domain\Services;

use App\Rpc\Domain\Enums\RpcEventEnum;
use App\Rpc\Domain\Events\RpcRequestEvent;
use App\Rpc\Domain\Events\RpcResponseEvent;
use App\Security\Domain\Entities\MethodEntity;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use yii\web\IdentityInterface;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Entities\User;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Libs\Event\Traits\EventDispatcherTrait;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Domain\Libs\Query;
use ZnCore\Domain\Traits\RepositoryAwareTrait;
use ZnCrypt\Base\Domain\Exceptions\InvalidPasswordException;
use ZnCrypt\Base\Domain\Services\PasswordService;
use ZnCrypt\Jwt\Domain\Entities\JwtEntity;
use ZnCrypt\Jwt\Domain\Services\JwtService;
use ZnLib\Rpc\Domain\Entities\RpcRequestEntity;
use ZnLib\Rpc\Domain\Entities\RpcResponseEntity;

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
        $this->identityEntity = $identityEntity;
    }

    public function getIdentity(): IdentityEntityInterface
    {
        if($this->isGuest()) {
            throw new UnauthorizedException();
        }
        return $this->identityEntity;
    }

    public function isGuest(): bool
    {
        return !is_object($this->identityEntity);
    }

    public function logout()
    {
        $this->identityEntity = null;
        $this->logger->info('auth logout');
    }

    public function tokenByForm(AuthForm $loginForm): TokenEntity
    {
        //ValidationHelper::validateEntity($loginForm);

        $authEvent = new AuthEvent($loginForm);
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);
        //dd(4477);
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
        //$this->setIdentity($userEntity);

        $token = $this->forgeTokenEntity($userEntity);
        $this->logger->info('auth tokenByForm');
        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::AFTER_AUTH_SUCCESS);
        return $token;
    }
    
    private function triggerBefore(RpcRequestEntity $requestEntity, MethodEntity $methodEntity) {
        
    }

    private function triggerAfter(RpcRequestEntity $requestEntity, RpcResponseEntity $responseEntity) {
        $responseEvent = new RpcResponseEvent($requestEntity, $responseEntity);
        $this->getEventDispatcher()->dispatch($responseEvent, RpcEventEnum::AFTER_RUN_ACTION);
    }
    
    public function authByForm(AuthForm $authForm)
    {
        
        try {
            $credentialEntity = $this->credentialRepository->oneByCredential($authForm->getLogin(), 'login');
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
        $this->verificationPasswordByCredential($credentialEntity, $authForm->getPassword());
        $userEntity = $this->identityRepository->oneById($credentialEntity->getIdentityId());
        $this->setIdentity($userEntity);
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

    public function authenticationByForm(LoginForm $loginForm)
    {
        $authForm = new AuthForm([
            'login' => $loginForm->login,
            'password' => $loginForm->password,
            'rememberMe' => $loginForm->rememberMe,
        ]);
        $this->authByForm($authForm);
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

}
