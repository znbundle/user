<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnCore\Domain\Query\Entities\Where;
use ZnCore\Domain\Query\Enums\OperatorEnum;
use ZnCore\Domain\Query\Entities\Query;
use ZnCore\Domain\Entity\Exceptions\NotFoundException;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;
use ZnBundle\User\Domain\Entities\ConfirmEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;

class ConfirmRepository extends BaseEloquentCrudRepository implements ConfirmRepositoryInterface
{

    public function tableName() : string
    {
        return 'user_confirm';
    }

    public function getEntityClass() : string
    {
        return ConfirmEntity::class;
    }

    public function deleteExpired() {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->where('expire', OperatorEnum::LESS_OR_EQUAL, time());
        $queryBuilder->delete();
    }

    public function findOneByUniqueAttributes(string $login, string $action): ConfirmEntity
    {
        $query = new Query;
        $query->whereNew(new Where('login', $login));
        $query->whereNew(new Where('action', $action));
        $collection = $this->findAll($query);
        if($collection->count() == 0) {
            throw new NotFoundException();
        }
        return $collection->first();
    }
}
