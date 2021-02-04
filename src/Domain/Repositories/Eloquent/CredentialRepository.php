<?php

namespace ZnBundle\User\Domain\Repositories\Eloquent;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Enums\CredentialTypeEnum;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnCore\Domain\Libs\Query;

class CredentialRepository extends \ZnLib\Db\Base\BaseEloquentCrudRepository implements CredentialRepositoryInterface
{

    public function tableName(): string
    {
        return 'user_credential';
    }

    public function getEntityClass(): string
    {
        return CredentialEntity::class;
    }

    public function oneByCredential(string $credential, string $type = CredentialTypeEnum::LOGIN): CredentialEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'credential' => $credential,
            'type' => $type,
        ]);
        return $this->one($query);
    }

    public function oneByValidation(string $validation, string $type): CredentialEntity
    {
        $query = new Query;
        $query->whereByConditions([
            'validation' => $validation,
            'type' => $type,
        ]);
        return $this->one($query);
    }
}
