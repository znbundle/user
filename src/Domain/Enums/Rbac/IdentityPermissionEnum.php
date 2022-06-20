<?php

namespace ZnBundle\User\Domain\Enums\Rbac;

use ZnCore\Base\Libs\Enum\Interfaces\GetLabelsInterface;

class IdentityPermissionEnum implements GetLabelsInterface
{

    const ALL = 'oUserIdentityAll';
    const ONE = 'oUserIdentityOne';
    const CREATE = 'oUserIdentityCreate';
    const UPDATE = 'oUserIdentityUpdate';
    const DELETE = 'oUserIdentityDelete';
//    const RESTORE = 'oUserIdentityRestore';

    public static function getLabels()
    {
        return [
            self::ALL => 'Пользователи. Просмотр списка',
            self::ONE => 'Пользователи. Просмотр записи',
            self::CREATE => 'Пользователи. Создание',
            self::UPDATE => 'Пользователи. Редактирование',
            self::DELETE => 'Пользователи. Удаление',
//            self::RESTORE => 'Пользователи. Восстановление',
        ];
    }
}