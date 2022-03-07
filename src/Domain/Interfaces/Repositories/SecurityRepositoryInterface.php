<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\SecurityEntity;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;

DeprecateHelper::softThrow();

interface SecurityRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByIdentityId(int $identityId): SecurityEntity;

}
