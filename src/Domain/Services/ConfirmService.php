<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface;
use PhpBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;

class ConfirmService extends BaseCrudService implements ConfirmServiceInterface
{

    public function __construct(ConfirmRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


}

