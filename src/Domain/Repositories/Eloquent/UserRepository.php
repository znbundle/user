<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\User\Domain\Entities\Identity;
use PhpBundle\User\Domain\Interfaces\UserRepositoryInterface;

class UserRepository extends BaseEloquentCrudRepository implements UserRepositoryInterface
{

    protected $tableName = 'fos_user';

    public function getEntityClass(): string
    {
        return Identity::class;
    }
}