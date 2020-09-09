<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Entities\SecurityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use ZnCore\Domain\Libs\Query;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Entities\IdentityEntity;

class SecurityRepository extends BaseEloquentCrudRepository implements SecurityRepositoryInterface
{

    protected $tableName = 'user_security';

    public function getEntityClass(): string
    {
        return SecurityEntity::class;
    }

    public function oneByIdentityId(int $identityId): SecurityEntity {
        $query = new Query;
        $query->where(['identity_id' => $identityId]);
        return $this->one($query);
    }
}
