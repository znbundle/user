<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\ConfirmEntity;
use ZnCore\Base\Exceptions\AlreadyExistsException;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;

interface ConfirmServiceInterface extends CrudServiceInterface
{

    /**
     * @param ConfirmEntity $confirmEntity
     * @throws AlreadyExistsException
     */
    public function add(ConfirmEntity $confirmEntity);
}

