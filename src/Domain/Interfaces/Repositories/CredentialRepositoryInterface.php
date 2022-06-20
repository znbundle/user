<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Enums\CredentialTypeEnum;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Libs\Repository\Interfaces\CrudRepositoryInterface;

interface CredentialRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param int $identityId
     * @param array|null $types
     * @return Collection | CredentialEntity[]
     */
    public function allByIdentityId(int $identityId, array $types = null): Collection;

    /**
     * @param string $credential
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function oneByCredential(string $credential, string $type = CredentialTypeEnum::LOGIN): CredentialEntity;

    public function oneByCredentialValue(string $credential): CredentialEntity;
    
    /**
     * @param string $validation
     * @param string $type
     * @return CredentialEntity
     * @throws NotFoundException
     */
    public function oneByValidation(string $validation, string $type): CredentialEntity;
}

