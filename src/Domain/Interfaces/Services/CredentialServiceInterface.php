<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;

interface CredentialServiceInterface
{

    public function oneByCredentialValue(string $credential): CredentialEntity;
}

