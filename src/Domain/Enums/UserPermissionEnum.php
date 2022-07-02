<?php

namespace ZnBundle\User\Domain\Enums;

use ZnCore\Base\Develop\Helpers\DeprecateHelper;
use ZnCore\Base\Enum\Interfaces\GetLabelsInterface;

DeprecateHelper::hardThrow();

class UserPermissionEnum implements GetLabelsInterface
{

    const IDENTITY_READ = 'oAccountIdentityRead';
    const IDENTITY_WRITE = 'oAccountIdentityWrite';

    public static function getLabels()
    {
        return [
            self::IDENTITY_READ => 'Пользователь. Чтение',
            self::IDENTITY_WRITE => 'Пользователь. Запись',
        ];
    }
}