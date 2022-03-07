<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Entities\SecurityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use ZnCore\Base\Exceptions\DeprecatedException;
use ZnCore\Domain\Libs\Query;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;

throw new DeprecatedException();

class SecurityRepository extends BaseEloquentCrudRepository implements SecurityRepositoryInterface
{

    protected $tableName = 'user_security';

    public function getEntityClass(): string
    {
        return SecurityEntity::class;
    }

    public function oneByIdentityId(int $identityId): SecurityEntity
    {
        $query = new Query;
        $query->whereByConditions(['identity_id' => $identityId]);
        return $this->one($query);
    }
}
