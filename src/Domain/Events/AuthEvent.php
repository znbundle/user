<?php

namespace ZnBundle\User\Domain\Events;

use Symfony\Contracts\EventDispatcher\Event;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnCore\Domain\Traits\Event\EventSkipHandleTrait;

class AuthEvent extends Event
{

    use EventSkipHandleTrait;

    private $loginForm;

    public function __construct(AuthForm $loginForm)
    {
        $this->loginForm = $loginForm;
    }

    public function getLoginForm(): AuthForm
    {
        return $this->loginForm;
    }
}
