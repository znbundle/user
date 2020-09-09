<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnCore\Domain\Base\BaseCrudService;

class IdentityService extends BaseCrudService implements IdentityServiceInterface
{

    public function __construct(IdentityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function updateById($id, $data)
    {
        unset($data['password']);
        return parent::updateById($id, $data);
    }
}
