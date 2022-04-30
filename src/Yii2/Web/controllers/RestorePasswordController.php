<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnBundle\User\Yii2\Web\forms\RestorePasswordForm;
use ZnLib\Rest\Yii2\Helpers\Behavior;

/**
 * PasswordController controller
 */
class RestorePasswordController extends Controller
{

    const SESSION_KEY = 'restore-password';

    public $defaultAction = 'request';
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
            'access' => Behavior::access('?'),
        ];
    }

    public function actionRequest()
    {
        $model = new RestorePasswordForm();
        $model->setScenario(RestorePasswordForm::SCENARIO_REQUEST);
        if (Yii::$app->request->isPost) {
            $body = Yii::$app->request->post('RestorePasswordForm');
            $model->setAttributes($body, false);
            try {
                \App::$domain->account->restorePassword->request($model->login);
                $session['login'] = $model->login;
                Yii::$app->session->set(self::SESSION_KEY, $session);
                return $this->redirect(['/user/restore-password/check']);
            } catch (UnprocessableEntityHttpException $e) {
                $model->addErrorsFromException($e);
            }
        }
        return $this->render('request', ['model' => $model]);
    }

    public function actionCheck()
    {
        $model = new RestorePasswordForm();
        $model->setScenario(RestorePasswordForm::SCENARIO_CHECK);
        $session = Yii::$app->session->get(self::SESSION_KEY);
        $model->login = $session['login'];
        if (Yii::$app->request->isPost) {
            $body = Yii::$app->request->post('RestorePasswordForm');
            $model->setAttributes($body, false);
            try {

                \App::$domain->account->restorePassword->checkActivationCode($model->login, $model->activation_code);
                $session['activation_code'] = $model->activation_code;
                Yii::$app->session->set(self::SESSION_KEY, $session);
                return $this->redirect(['/user/restore-password/confirm']);
            } catch (UnprocessableEntityHttpException $e) {
                $model->addErrorsFromException($e);
            }
        }
        return $this->render('check', ['model' => $model]);
    }

    public function actionConfirm()
    {
        $model = new RestorePasswordForm();
        $model->setScenario(RestorePasswordForm::SCENARIO_CONFIRM);
        $session = Yii::$app->session->get(self::SESSION_KEY);
        $model->login = $session['login'];
        $model->activation_code = $session['activation_code'];
        if (Yii::$app->request->isPost) {
            $body = Yii::$app->request->post('RestorePasswordForm');
            $model->setAttributes($body, false);
            try {
                \App::$domain->account->restorePassword->confirm($model->login, $model->activation_code, $model->password);
                $this->toastrService->success(['account/restore-password', 'new_password_saved_success']);
                return $this->redirect('/' . Yii::$app->user->loginUrl[0]);
            } catch (UnprocessableEntityHttpException $e) {
                $model->addErrorsFromException($e);
            }
        }
        return $this->render('reset', [
            'model' => $model,
        ]);
    }

}
