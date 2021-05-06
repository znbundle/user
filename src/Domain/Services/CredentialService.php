<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\CredentialServiceInterface;
use ZnCore\Domain\Base\BaseService;

class CredentialService extends BaseService implements CredentialServiceInterface
{

    public function __construct(CredentialRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    public function oneByCredentialValue(string $credential): CredentialEntity
    {
        return $this->getRepository()->oneByCredentialValue($credential);
    }
}
