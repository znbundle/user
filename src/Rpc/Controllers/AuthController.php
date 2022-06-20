<?php

namespace ZnBundle\User\Rpc\Controllers;

use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\Entity\Helpers\EntityHelper;
use ZnLib\Rpc\Domain\Entities\RpcRequestEntity;
use ZnLib\Rpc\Domain\Entities\RpcResponseEntity;
use ZnLib\Rpc\Rpc\Base\BaseRpcController;

class AuthController extends BaseRpcController
{

    public function __construct(AuthServiceInterface $authService)
    {
        $this->service = $authService;
    }

    public function attributesOnly(): array
    {
        return [
            'token',
            'identity.id',
//            'identity.logo',
            'identity.statusId',
            'identity.username',
            'identity.roles',
//            'identity.assignments',
        ];
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
        return $this->serializeResult($result);
    }

    public function getToken(RpcRequestEntity $requestEntity): RpcResponseEntity
    {
        $form = new AuthForm();
        EntityHelper::setAttributes($form, $requestEntity->getParams());
        /** @var TokenValueEntity $tokenEntity */
        $tokenEntity = $this->service->tokenByForm($form);
        $result = [];
        $result['token'] = $tokenEntity->getTokenString();
        $result['identity'] = $tokenEntity->getIdentity();
        return $this->serializeResult($result);
    }
}
