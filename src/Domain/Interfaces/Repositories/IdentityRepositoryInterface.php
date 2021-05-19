<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;
use ZnCore\Domain\Libs\Query;

interface IdentityRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param string $username
     * @param Query|null $query
     * @return IdentityEntity
     * @deprecated
     */
    public function findUserByUsername(string $username, Query $query = null): IdentityEntity;

    //public function findUserBy(array $condition, Query $query = null): IdentityEntity;
}
