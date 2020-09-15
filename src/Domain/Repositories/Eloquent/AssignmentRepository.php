<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Entities\AssignmentEntity;
use ZnCore\Db\Db\Base\BaseEloquentCrudRepository;

class AssignmentRepository extends BaseEloquentCrudRepository /*implements AssignmentRepositoryInterface*/
{

    protected $tableName = 'auth_assignment';

    public function getEntityClass(): string
    {
        return AssignmentEntity::class;
    }
}
