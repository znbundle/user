<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnCore\Domain\Libs\Query;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;

class IdentityRepository extends BaseEloquentCrudRepository implements IdentityRepositoryInterface
{

    protected $tableName = 'user_identity';

    public function getEntityClass(): string
    {
        return IdentityEntity::class;
    }

    public function findUserByUsername(string $username): IdentityEntity {
        return $this->findUserBy(['login' => $username]);
    }

    public function findUserBy(array $condition): IdentityEntity {
        $query = new Query;
        $query->whereFromCondition($condition);
        return $this->one($query);
    }
}
