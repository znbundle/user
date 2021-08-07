<?php

namespace ZnBundle\User\Rpc\Controllers;

use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Rpc\Domain\Entities\RpcRequestEntity;
use ZnLib\Rpc\Domain\Entities\RpcResponseEntity;

class AuthController
{

    private $service;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->service = $authService;
    }

    public function getTokenByPassword(RpcRequestEntity $requestEntity): RpcResponseEntity
    {
        $form = new AuthForm();
        EntityHelper::setAttributes($form, $requestEntity->getParams());
        /** @var TokenValueEntity $tokenEntity */
        $tokenEntity = $this->service->tokenByForm($form);
        $result = [
            'token' => $tokenEntity->getTokenString()
        ];
        $response = new RpcResponseEntity();
        $response->setResult($result);
        return $response;
    }
}
