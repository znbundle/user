<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnCore\Base\Exceptions\NotFoundException;

interface CredentialServiceInterface
{
    /**
     * @param int $identityId
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function oneByIdentityIdAndType(int $identityId, string $type): CredentialEntity;

    /**
     * @param string $credential
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function oneByCredentialValue(string $credential): CredentialEntity;

    public function hasByCredentialValue(string $credential): bool;
}

