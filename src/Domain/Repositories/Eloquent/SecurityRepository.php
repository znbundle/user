<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpBundle\User\Domain\Entities\SecurityEntity;
use PhpBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpBundle\User\Domain\Entities\IdentityEntity;

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
