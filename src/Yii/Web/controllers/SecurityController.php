<?php

namespace ZnBundle\User\Yii\Web\controllers;

use yii\web\Controller;
use ZnLib\Rest\Yii2\Helpers\Behavior;
use yii2bundle\account\domain\v3\entities\SecurityEntity;
use ZnBundle\User\Yii\Web\forms\ChangePasswordForm;
use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert;
use yii2bundle\account\domain\v3\forms\ChangeEmailForm;
use ZnBundle\User\Yii\Web\helpers\SecurityMenu;

class SecurityController extends Controller {
	
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => Behavior::access('@'),
		];
	}
	
	public function actionIndex()
	{
		$menuInstance = new SecurityMenu();
		$menu = $menuInstance->toArray();
		$url = $menu[0]['url'];
		$this->redirect([SL . $url]);
	}
	
	public function actionEmail()
	{
		$model = new ChangeEmailForm();
		$body = Yii::$app->request->post('ChangeEmailForm');
		if (!empty($body)) {
			$model->setAttributes($body, false);
			if($model->validate()) {
				try {
					\App::$domain->account->security->changeEmail($model->getAttributes());
					\ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert::create(['account/security', 'email_changed_success'], Alert::TYPE_SUCCESS);
				} catch (UnprocessableEntityHttpException $e) {
					$model->addErrorsFromException($e);
				}
			}
		} else {
			/** @var SecurityEntity $securityEntity */
			$securityEntity = \App::$domain->account->security->oneById(Yii::$app->user->id);
			$model->email = $securityEntity->email;
		}
		return $this->render('email', [
			'model' => $model,
		]);
	}
	
	public function actionPassword()
	{
		$model = new ChangePasswordForm();
		$body = Yii::$app->request->post('ChangePasswordForm');
		if(!empty($body)) {
			$model->setAttributes($body, false);
			if($model->validate()) {
				$bodyPassword = $model->getAttributes(['password', 'new_password']);
				try {
					\App::$domain->account->security->changePassword($bodyPassword);
					\ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert::create(['account/security', 'password_changed_success'], Alert::TYPE_SUCCESS);
				} catch (UnprocessableEntityHttpException $e) {
					$model->addErrorsFromException($e);
				}
			}
		}
		return $this->render('password', [
			'model' => $model,
		]);
	}
	
}