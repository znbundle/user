<?php

namespace ZnBundle\User\Domain\Events;

use Symfony\Contracts\EventDispatcher\Event;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use ZnCore\Base\EventDispatcher\Traits\EventSkipHandleTrait;

class AuthEvent extends Event
{

    use EventSkipHandleTrait;

    private $loginForm;
    private $identityEntity;

    public function __construct(AuthForm $loginForm)
    {
        $this->loginForm = $loginForm;
    }

    public function getLoginForm(): AuthForm
    {
        return $this->loginForm;
    }

    public function getIdentityEntity(): ?IdentityEntityInterface
    {
        return $this->identityEntity;
    }

    public function setIdentityEntity(IdentityEntityInterface $identityEntity): void
    {
        $this->identityEntity = $identityEntity;
    }
}
