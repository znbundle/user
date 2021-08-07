<?php

namespace ZnBundle\User\Rpc\Controllers;

use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnLib\Rpc\Rpc\Base\BaseCrudRpcController;

class IdentityController extends BaseCrudRpcController
{

    public function __construct(IdentityServiceInterface $authService)
    {
        $this->service = $authService;
    }
}
