<?php

namespace ZnBundle\User\Domain\Enums\Rbac;

use ZnCore\Base\Interfaces\GetLabelsInterface;

class AppUserPermissionEnum implements GetLabelsInterface
{

    const AUTHENTICATION_GET_TOKEN_BY_PASSWORD = 'oAuthenticationGetTokenByPassword';
    const AUTHENTICATION_WEB_LOGIN = 'oAuthenticationWebLogin';
    const AUTHENTICATION_WEB_LOGOUT = 'oAuthenticationWebLogout';

    public static function getLabels()
    {
        return [
            self::AUTHENTICATION_GET_TOKEN_BY_PASSWORD => 'Аутентификация. Получение токена по паролю',
            self::AUTHENTICATION_WEB_LOGIN => 'Аутентификация. Вход в аккаунт',
            self::AUTHENTICATION_WEB_LOGOUT => 'Аутентификация. Выход из аккаунта',
        ];
    }
}