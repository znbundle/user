<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnBundle\User\Yii2\Web\forms\ChangePasswordForm;
use ZnBundle\User\Yii2\Web\helpers\SecurityMenu;
use ZnLib\Rest\Yii2\Helpers\Behavior;

class SecurityController extends Controller {

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
                    $this->toastrService->success(['account/security', 'email_changed_success']);
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
                    $this->toastrService->success(['account/security', 'password_changed_success']);
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