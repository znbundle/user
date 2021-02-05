<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use yii\web\Controller;
use ZnLib\Rest\Yii2\Helpers\Behavior;
use yii2bundle\account\domain\v3\entities\SecurityEntity;
use ZnBundle\User\Yii2\Web\forms\ChangePasswordForm;
use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use ZnYii\Web\Widgets\Toastr\Toastr;
use yii2bundle\account\domain\v3\forms\ChangeEmailForm;
use ZnBundle\User\Yii2\Web\helpers\SecurityMenu;

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
		$this->redirect(['/' . $url]);
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
					\ZnYii\Web\Widgets\Toastr\Toastr::create(['account/security', 'email_changed_success'], Toastr::TYPE_SUCCESS);
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
					\ZnYii\Web\Widgets\Toastr\Toastr::create(['account/security', 'password_changed_success'], Toastr::TYPE_SUCCESS);
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