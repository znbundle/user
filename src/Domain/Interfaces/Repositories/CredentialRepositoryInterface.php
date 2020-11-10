<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Enums\CredentialTypeEnum;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;

interface CredentialRepositoryInterface extends CrudRepositoryInterface
{

    public function oneByCredential(string $credential, string $type = CredentialTypeEnum::LOGIN): CredentialEntity;

}

