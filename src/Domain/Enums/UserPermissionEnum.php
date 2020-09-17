<?php

namespace ZnBundle\User\Domain\Enums;

use ZnCore\Domain\Base\BaseEnum;

class UserPermissionEnum extends BaseEnum
{

    const IDENTITY_READ = 'oAccountIdentityRead';
    const IDENTITY_WRITE = 'oAccountIdentityWrite';

    public static function getLabels() {
        return [
            self::IDENTITY_READ => 'Пользователь. Чтение',
            self::IDENTITY_WRITE => 'Пользователь. Запись',
        ];
    }
}