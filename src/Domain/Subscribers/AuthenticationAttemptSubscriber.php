<?php

namespace ZnBundle\User\Domain\Subscribers;

use ZnBundle\User\Domain\Enums\UserNotifyTypeEnum;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ZnBundle\Summary\Domain\Exceptions\AttemptsBlockedException;
use ZnBundle\Summary\Domain\Interfaces\Services\AttemptServiceInterface;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Interfaces\Services\CredentialServiceInterface;
use ZnCore\Domain\Traits\EntityManagerTrait;
use ZnUser\Notify\Domain\Interfaces\Services\NotifyServiceInterface;

class AuthenticationAttemptSubscriber implements EventSubscriberInterface
{

    use EntityManagerTrait;

    public $action = 'authorization';
    public $attemptCount = 3;
    public $lifeTime = 30;

    private $attemptService;
    private $credentialService;
    private $notifyService;

    public function __construct(
        AttemptServiceInterface $attemptService,
        NotifyServiceInterface $notifyService,
        CredentialServiceInterface $credentialService
    )
    {
        $this->attemptService = $attemptService;
        $this->credentialService = $credentialService;
        $this->notifyService = $notifyService;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthEventEnum::BEFORE_AUTH => 'onBeforeAuth',
            //AuthEventEnum::AFTER_AUTH_SUCCESS => 'onAfterAuthSuccess',
            AuthEventEnum::AFTER_AUTH_ERROR => 'onAfterAuthError',
        ];
    }

    public function onBeforeAuth(AuthEvent $event)
    {

    }

    /*public function onAfterAuthSuccess(AuthEvent $event)
    {

    }*/

    public function onAfterAuthError(AuthEvent $event)
    {
        $login = $event->getLoginForm()->getLogin();
        $credentialEntity = $this->credentialService->oneByCredentialValue($login);
        try {
            $this->attemptService->check($credentialEntity->getIdentityId(), $this->action, $this->lifeTime, $this->attemptCount);
            //} catch (NotFoundException $e) {
        } catch (AttemptsBlockedException $e) {
            $this->notifyService->sendNotifyByTypeName(UserNotifyTypeEnum::AUTHENTICATION_ATTEMPT_BLOCK, $credentialEntity->getIdentityId());
            throw $e;
        }
    }
}
