<?php

namespace ZnBundle\User\Domain\Interfaces\Repositories;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Domain\Interfaces\Repository\CrudRepositoryInterface;

interface TokenRepositoryInterface extends CrudRepositoryInterface
{

    /**
     * @param string $value
     * @param string $type
     * @return TokenEntity
     * @throws NotFoundException
     */
    public function oneByValue(string $value, string $type): TokenEntity;
}
