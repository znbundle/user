<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnCore\Domain\Entities\Query\Where;
use ZnCore\Domain\Enums\OperatorEnum;
use ZnCore\Domain\Libs\Query;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnLib\Db\Base\BaseEloquentCrudRepository;
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

    public function oneByUniqueAttributes(string $login, string $action): ConfirmEntity
    {
        $query = new Query;
        $query->whereNew(new Where('login', $login));
        $query->whereNew(new Where('action', $action));
        $collection = $this->all($query);
        if($collection->count() == 0) {
            throw new NotFoundException();
        }
        return $collection->first();
    }
}
