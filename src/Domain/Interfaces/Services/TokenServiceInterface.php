<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;

interface TokenServiceInterface
{

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenEntity;
    public function getIdentityIdByToken(string $token): int;
}
