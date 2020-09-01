<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\User\Domain\Entities\IdentityEntity;
use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;

class AuthService extends BaseCrudService implements AuthServiceInterface
{

    /*public function __construct(IdentityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }*/

    public function getIdentity()
    {
        //$identity = new IdentityEntity;
        //$identity->setId(\Yii::$app->user->identity->getId());
        return \Yii::$app->user->identity;
    }

    public function authByIdentity(object $identity) {

    }
}
