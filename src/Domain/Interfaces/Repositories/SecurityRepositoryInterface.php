<?php

namespace PhpBundle\User\Domain\Interfaces\Repositories;

use PhpBundle\User\Domain\Entities\SecurityEntity;
use PhpLab\Core\Domain\Interfaces\Repository\CrudRepositoryInterface;

interface SecurityRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdentityId(int $identityId): SecurityEntity;

}
