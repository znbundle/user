<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;

interface TokenServiceInterface
{

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenValueEntity;
    public function getIdentityIdByToken(string $token): int;
}
