<?php

namespace ZnBundle\User\Yii2\Web;

use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Base\Libs\I18Next\Services\TranslationService;
use Yii;
use yii\base\Module as YiiModule;
use yii\web\NotFoundHttpException;

/**
 * user module definition class
 */
class Module extends YiiModule
{

	public function beforeAction($action)
	{

        //$i18n = new TranslationService;

        //I18Next::t('user', 'main.title');
        //
        // $i18n->t('account', 'main.title')
        // Yii::t($moduleId . SL . $controllerId, 'title')

		$controllerId = Yii::$app->controller->id;
		$moduleId = 'account';
		Yii::$app->view->title = I18Next::t('user', 'main.title');


		//\App::$domain->navigation->breadcrumbs->create(Yii::$app->view->title);
		//\App::$domain->navigation->breadcrumbs->create(I18Next::t($moduleId, "{$controllerId}.title"));

		return parent::beforeAction($action);
	}
}
