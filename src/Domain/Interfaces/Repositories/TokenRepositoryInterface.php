<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnCore\Domain\Entity\Exceptions\NotFoundException;
use ZnCore\Domain\Repository\Interfaces\CrudRepositoryInterface;

interface TokenRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param string $value
     * @param string $type
     * @return TokenEntity
     * @throws NotFoundException
     */
    public function findOneByValue(string $value, string $type): TokenEntity;
}
