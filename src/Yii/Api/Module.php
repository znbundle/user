<?php

namespace PhpBundle\User\Yii\Api;

use yii\base\Module as YiiModule;
use yii\filters\AccessControl;

class Module extends YiiModule {

    /**
     * {@inheritdoc}
     */
    /*public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }*/
}
