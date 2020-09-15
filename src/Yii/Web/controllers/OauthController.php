<?php

namespace ZnBundle\User\Yii\Web\controllers;

use yii\authclient\BaseOAuth;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use ZnSandbox\Sandbox\Html\Yii2\Widgets\Toastr\widgets\Alert;

class OauthController extends Controller {
	
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['login'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
		];
	}
	
	public function actions() {
		return [
			'login' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'onLoginSuccess'],
				'cancelCallback' => [$this, 'onLoginCancel'],
			],
		];
	}
	
	public function init() {
		if(!\App::$domain->account->oauth->isEnabled()) {
			throw new ServerErrorHttpException('Auth clients not defined');
		}
		parent::init();
	}
	
	public function onLoginSuccess(BaseOAuth $client) {
		\App::$domain->account->oauth->authByClient($client);
		\ZnSandbox\Sandbox\Html\Yii2\Widgets\Toastr\widgets\Alert::create(['account/auth', 'login_success'], Alert::TYPE_SUCCESS);
	}
	
	public function onLoginCancel() {
		\ZnSandbox\Sandbox\Html\Yii2\Widgets\Toastr\widgets\Alert::create(['account/auth', 'login_access_error'], Alert::TYPE_DANGER);
	}
	
}
