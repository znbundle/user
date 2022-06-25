<?php

namespace ZnBundle\User\Yii2\Web;

use Yii;
use yii\base\Module as YiiModule;
use ZnLib\Components\I18Next\Facades\I18Next;

class Module extends YiiModule
{

    public function beforeAction($action)
    {
        Yii::$app->view->title = I18Next::t('user', 'main.title');
        return parent::beforeAction($action);
    }
}
