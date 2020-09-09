<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\SecurityEntity;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;

interface SecurityRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdentityId(int $identityId): SecurityEntity;

}
