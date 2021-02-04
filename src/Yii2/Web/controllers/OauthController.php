<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use yii\authclient\BaseOAuth;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use ZnYii\Web\Widgets\Toastr\Alert;

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
		\ZnYii\Web\Widgets\Toastr\Alert::create(['account/auth', 'login_success'], Alert::TYPE_SUCCESS);
	}
	
	public function onLoginCancel() {
		\ZnYii\Web\Widgets\Toastr\Alert::create(['account/auth', 'login_access_error'], Alert::TYPE_DANGER);
	}
	
}
