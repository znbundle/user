<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpLab\Core\Domain\Libs\Query;
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

    public function findUserByUsername(string $username): IdentityEntity {
        return $this->findUserBy(['login' => $username]);
    }

    public function findUserBy(array $condition): IdentityEntity {
        $query = new Query;
        $query->whereFromCondition($condition);
        return $this->one($query);
    }
}
