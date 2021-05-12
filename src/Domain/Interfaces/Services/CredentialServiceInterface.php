<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;

interface CredentialServiceInterface
{

    public function oneByIdentityIdAndType(int $identityId, string $type): CredentialEntity;

    public function oneByCredentialValue(string $credential): CredentialEntity;
}

