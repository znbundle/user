<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\ConfirmEntity;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;

interface ConfirmRepositoryInterface extends CrudRepositoryInterface
{

    public function deleteExpired();

    public function oneByUnique(string $login, string $action): ConfirmEntity;
}

