<?php

namespace ZnBundle\User\Yii2\Entities;

use Illuminate\Container\Container;
use yii\web\IdentityInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;

class LoginEntity extends IdentityEntity implements IdentityInterface
{

    public static function findIdentity($id)
    {
        /** @var IdentityRepositoryInterface $repository */
        $repository = Container::getInstance()->get(IdentityRepositoryInterface::class);
        return $repository->oneById($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return intval($this->id);
    }

    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
