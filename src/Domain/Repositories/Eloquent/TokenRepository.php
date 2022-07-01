<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\TokenRepositoryInterface;
use ZnCore\Domain\Query\Entities\Query;
use ZnDatabase\Eloquent\Domain\Base\BaseEloquentCrudRepository;

class TokenRepository extends BaseEloquentCrudRepository implements TokenRepositoryInterface
{

    public function tableName(): string
    {
        return 'user_token';
    }

    public function getEntityClass(): string
    {
        return TokenEntity::class;
    }

    public function oneByValue(string $value, string $type): TokenEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'value' => $value,
            'type' => $type,
        ]);
        return $this->findOne($query);
    }
}
