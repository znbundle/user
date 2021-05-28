<?php

namespace ZnBundle\User\Yii2\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use yii\web\IdentityInterface;
use yii\web\User;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Events\IdentityEvent;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;

class AuthenticationIdentitySubscriber implements EventSubscriberInterface
{

    private $yiiUser;

    public function __construct(User $yiiUser)
    {
        $this->yiiUser = $yiiUser;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthEventEnum::BEFORE_GET_IDENTITY => 'onBeforeGetIdentity',
            AuthEventEnum::BEFORE_IS_GUEST => 'onBeforeGuest',
            AuthEventEnum::AFTER_LOGOUT => 'onAfterLogout',
            AuthEventEnum::AFTER_AUTH_SUCCESS => 'onAfterAuthSuccess',
        ];
    }

    public function onAfterAuthSuccess(AuthEvent $event)
    {
        /** @var IdentityInterface $identity */
        $identity = $event->getIdentityEntity();
        $this->yiiUser->login($identity);
    }

    public function onBeforeGetIdentity(IdentityEvent $event)
    {
        /** @var IdentityEntityInterface $identity */
        $identity = $this->yiiUser->identity;
        if ($identity) {
            $event->setIdentityEntity($identity);
        }
    }

    public function onBeforeGuest(IdentityEvent $event)
    {
        $identity = $this->yiiUser->identity;
        $event->setIsGuest(empty($identity));
    }

    public function onAfterLogout(IdentityEvent $event)
    {
        $this->yiiUser->logout();
    }
}
