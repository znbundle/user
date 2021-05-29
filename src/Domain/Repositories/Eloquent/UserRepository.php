<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnCore\Base\Helpers\DeprecateHelper;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\User\Domain\Entities\Identity;
use ZnBundle\User\Domain\Interfaces\Repositories\UserRepositoryInterface;

DeprecateHelper::hardThrow();

class UserRepository extends BaseEloquentCrudRepository implements UserRepositoryInterface
{

    protected $tableName = 'fos_user';

    public function getEntityClass(): string
    {
        return Identity::class;
    }
}