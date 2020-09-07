<?php

namespace PhpBundle\User\Domain\Repositories\Eloquent;

use PhpLab\Core\Domain\Entities\Query\Where;
use PhpLab\Core\Domain\Enums\OperatorEnum;
use PhpLab\Core\Domain\Libs\Query;
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

}
