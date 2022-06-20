<?php

namespace ZnBundle\User\Domain\Events;

use Symfony\Contracts\EventDispatcher\Event;
use ZnCore\Base\Libs\EventDispatcher\Traits\EventSkipHandleTrait;

class UserActionEvent extends Event
{

    use EventSkipHandleTrait;

    private $identityId;
    private $actionName;
    private $data;

    public function __construct(int $identityId, string $actionName, $data = null)
    {
        $this->identityId = $identityId;
        $this->actionName = $actionName;
        $this->data = $data;
    }

    public function getIdentityId()
    {
        return $this->identityId;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getData()
    {
        return $this->data;
    }
}
