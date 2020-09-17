<?php

namespace ZnBundle\User\Domain\Enums;

use ZnCore\Domain\Base\BaseEnum;

class UserRoleEnum extends BaseEnum
{

    // Администратор системы
    const ADMINISTRATOR = 'rAdministrator';

    // Идентифицированный пользователь
    const USER = 'rUser';

    // Гость системы
    const GUEST = 'rGuest';

    // Не идентифицированный пользователь
    const UNKNOWN_USER = 'rUnknownUser';

    // Корневой администратор системы
    const ROOT = 'rRoot';

    // Модератор системы
    const MODERATOR = 'rModerator';

    // Разработчик
    const DEVELOPER = 'rDeveloper';

}