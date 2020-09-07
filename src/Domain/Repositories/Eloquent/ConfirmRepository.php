<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpLab\Core\Domain\Entities\Query\Where;
use PhpLab\Core\Domain\Enums\OperatorEnum;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Exceptions\NotFoundException;
use PhpLab\Eloquent\Db\Base\BaseEloquentCrudRepository;
use PhpBundle\User\Domain\Entities\ConfirmEntity;
use PhpBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;

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

    public function oneByUnique(string $login, string $action): ConfirmEntity
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
