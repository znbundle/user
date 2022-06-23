<?php

namespace ZnBundle\User\Yii2\Entities;

use Illuminate\Container\Container;
use yii\web\IdentityInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnCore\Base\Container\Helpers\ContainerHelper;

class YiiIdentityEntity extends IdentityEntity implements IdentityInterface
{

    public static function findIdentity($id)
    {
        /** @var IdentityRepositoryInterface $repository */
        $repository = ContainerHelper::getContainer()->get(IdentityRepositoryInterface::class);
        return $repository->oneById($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
    }

}
