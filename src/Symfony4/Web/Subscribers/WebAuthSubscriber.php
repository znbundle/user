<?php

namespace ZnBundle\User\Symfony4\Web\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use ZnBundle\User\Domain\Enums\WebCookieEnum;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnCore\Base\Libs\DotEnv\Domain\Libs\DotEnv;
use ZnCore\Base\Libs\Entity\Helpers\EntityHelper;
use ZnLib\Web\Symfony4\MicroApp\Enums\ControllerEventEnum;
//use ZnLib\Web\Symfony4\MicroApp\Events\ControllerEvent;
use ZnLib\Web\Symfony4\MicroApp\Libs\CookieValue;

class WebAuthSubscriber implements EventSubscriberInterface
{

    private $authService;
    private $identityService;
    private $session;

    public function __construct(
        AuthServiceInterface $authService,
        IdentityServiceInterface $identityService,
        SessionInterface $session
    )
    {
        $this->authService = $authService;
        $this->identityService = $identityService;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEventEnum::BEFORE_ACTION => 'onBeforeRunAction',
        ];
    }

    public function onBeforeRunAction(ControllerEvent $event)
    {
        $identityArray = $this->session->get('user.identity');
        if (!$identityArray) {
            $identityIdCookie = $event->getRequest()->cookies->get(WebCookieEnum::IDENTITY_ID);
            if ($identityIdCookie) {
                try {
                    $cookieValue = new CookieValue(DotEnv::get('CSRF_TOKEN_ID'));
                    $identityId = $cookieValue->decode($identityIdCookie);
                    $identity = $this->identityService->oneById($identityId);
                    $this->authService->setIdentity($identity);
                    $this->session->set('user.identity', EntityHelper::toArray($identity));
                } catch (\DomainException $e) {
                }
            }
        }
    }
}
