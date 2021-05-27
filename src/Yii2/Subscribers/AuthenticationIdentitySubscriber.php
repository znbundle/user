<?php

namespace ZnBundle\User\Yii2\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Yii;
use ZnBundle\User\Domain\Enums\AuthEventEnum;
use ZnBundle\User\Domain\Events\AuthEvent;
use ZnBundle\User\Domain\Events\IdentityEvent;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Traits\EntityManagerTrait;

class AuthenticationIdentitySubscriber implements EventSubscriberInterface
{

    /*use EntityManagerTrait;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->setEntityManager($em);
    }*/

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
        Yii::$app->user->login($event->getIdentityEntity());
    }

    public function onBeforeGetIdentity(IdentityEvent $event)
    {
        $identity = Yii::$app->user->identity;
        if ($identity) {
            $event->setIdentityEntity($identity);
        }
    }

    public function onBeforeGuest(IdentityEvent $event)
    {
        $identity = Yii::$app->user->identity;
        $event->setIsGuest(empty($identity));
    }

    public function onAfterLogout(IdentityEvent $event)
    {
        Yii::$app->user->logout();
    }
}
