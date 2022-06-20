<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use ZnCore\Base\Libs\Repository\Interfaces\CrudRepositoryInterface;
use ZnCore\Base\Libs\Query\Entities\Query;

interface IdentityRepositoryInterface extends CrudRepositoryInterface
{

    public function findUserByUsername(string $username, Query $query = null): IdentityEntityInterface;

    //public function findUserBy(array $condition, Query $query = null): IdentityEntity;
}
