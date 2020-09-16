<?php

namespace ZnBundle\User\Yii\Web\controllers;

use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii2bundle\account\domain\v3\enums\AccountConfirmActionEnum;
use yii2bundle\account\domain\v3\forms\RegistrationForm;
use yii2bundle\account\domain\v3\services\RegistrationService;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use ZnBundle\User\Yii\Web\forms\SetSecurityForm;
use ZnLib\Rest\Yii2\Helpers\Behavior;
use ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert;

class RegistrationController extends Controller
{

    public $defaultAction = 'create';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => Behavior::access('?'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RegistrationForm();
        $model->scenario = RegistrationForm::SCENARIO_CHECK;
        $callback = function ($model) {
            \App::$domain->account->registration->activateAccount($model->login, $model->activation_code);
            $session['login'] = $model->login;
            $session['activation_code'] = $model->activation_code;
            Yii::$app->session->set('registration', $session);
            return $this->redirect(['/user/registration/set-password']);
        };
        $this->validateForm($model, $callback);
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSetPassword()
    {
        $session = Yii::$app->session->get('registration');
        if (empty($session['login']) || empty($session['activation_code'])) {
            return $this->redirect(['/user/registration']);
        }
        $isExists = \App::$domain->account->confirm->isHas($session['login'], AccountConfirmActionEnum::REGISTRATION);
        if (!$isExists) {
            \ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert::create(['account/registration', 'temp_user_not_found'], Alert::TYPE_DANGER);
            return $this->redirect(['/user/registration']);
        }
        $model = new SetSecurityForm();
        $callback = function ($model) use ($session) {
            \App::$domain->account->registration->createTpsAccount($session['login'], $session['activation_code'], $model->password, $model->email);
            \App::$domain->account->auth->authenticationFromWeb($session['login'], $model->password, true);
            \ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert::create(['account/registration', 'registration_success'], Alert::TYPE_SUCCESS);
            return $this->goHome();
        };
        $this->validateForm($model, $callback);
        return $this->render('set_password', [
            'model' => $model,
            'login' => $session['login'],
        ]);
    }

    private function validateForm(Model $form, $callback)
    {
        $body = Yii::$app->request->post();
        $isValid = $form->load($body) && $form->validate();
        if ($isValid) {
            try {
                return call_user_func_array($callback, [$form]);
            } catch (UnprocessableEntityHttpException $e) {
                $form->addErrorsFromException($e);
            }
        }
    }
}
