<?php

namespace ZnBundle\User\Domain\Helpers;

use ZnBundle\User\Domain\Entities\TokenValueEntity;

class TokenHelper
{

    public static function parseToken(string $token): TokenValueEntity
    {
        list($tokenType, $tokenValue) = explode(' ', $token);
        return new TokenValueEntity($tokenValue, $tokenType);
    }
}
