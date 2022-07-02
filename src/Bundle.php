<?php

namespace ZnBundle\User;

use ZnCore\Base\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function i18next(): array
    {
        return [
            'user' => 'vendor/znbundle/user/src/Domain/i18next/__lng__/__ns__.json',
        ];
    }

    /*public function migration(): array
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
    }*/
}
