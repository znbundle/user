<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Interfaces\Services\CredentialServiceInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnCore\Domain\Base\BaseService;

class CredentialService extends BaseService implements CredentialServiceInterface
{

    public function __construct(CredentialRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


}

