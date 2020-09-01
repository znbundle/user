<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpBundle\User\Domain\Entities\IdentityEntity;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';

    public function getEntityClass(): string
    {
        return IdentityEntity::class;
    }
}

