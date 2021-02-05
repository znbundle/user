<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use yii\authclient\BaseOAuth;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;

class OauthController extends Controller {

    private $toastrService;

    public function __construct(
        string $id,
        Module $module, array $config = [],
        ToastrServiceInterface $toastrService
    )
    {
        parent::__construct($id, $module, $config);
        $this->toastrService = $toastrService;
    }

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
        $this->toastrService->success(['account/auth', 'login_success']);
	}
	
	public function onLoginCancel() {
        $this->toastrService->error(['account/auth', 'login_access_error']);
	}
	
}
