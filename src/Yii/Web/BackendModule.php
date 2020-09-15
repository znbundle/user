<?php

namespace ZnBundle\User\Yii\Web;

/**
 * user module definition class
 */
class BackendModule extends Module
{

    public $controllerMap = [
        'auth' => [
            'class' => 'ZnBundle\User\Yii\Web\controllers\AuthController',
            'layout' => '@yii2bundle/applicationTemplate/backend/views/layouts/singleForm.php',
        ],
    ];

}
