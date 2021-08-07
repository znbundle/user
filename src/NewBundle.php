<?php

namespace ZnBundle\User;

use ZnCore\Base\Libs\App\Base\BaseBundle;

class NewBundle extends BaseBundle
{

    public function symfonyRpc(): array
    {
        return [
            __DIR__ . '/Rpc/config/identity-routes.php',
            __DIR__ . '/Rpc/config/account-routes.php',
//            __DIR__ . '/Rpc/config/person-routes.php',
        ];
    }

    public function symfonyWeb(): array
    {
        return [
            __DIR__ . '/Symfony4/Web/config/routing.php',
        ];
    }

    public function i18next(): array
    {
        return [
            'user' => 'vendor/znbundle/user/src/Domain/i18next/__lng__/__ns__.json',
        ];
    }

    public function migration(): array
    {
        return [
            '/vendor/znbundle/user/src/Domain/Migrations',
        ];
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/new-container.php',
        ];
    }
}
