<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use PhpBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;

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
