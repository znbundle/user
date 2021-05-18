<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Entities\AssignmentEntity;
use ZnCore\Domain\Libs\Query;
use ZnLib\Db\Base\BaseEloquentCrudRepository;

class AssignmentRepository extends BaseEloquentCrudRepository /*implements AssignmentRepositoryInterface*/
{

    protected $tableName = 'auth_assignment';

    public function getEntityClass(): string
    {
        return AssignmentEntity::class;
    }

    public function allByIdentityId(int $identityId): Collection {
        $query = new Query();
        $query->where('user_id', $identityId);
        return $this->all($query);
    }
}
