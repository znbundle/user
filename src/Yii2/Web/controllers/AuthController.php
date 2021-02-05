<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnLib\Rest\Yii2\Helpers\Behavior;
use ZnLib\Web\Yii2\Helpers\ErrorHelper;
use ZnYii\Web\Widgets\Toastr\Toastr;

class AuthController extends Controller
{

    public $defaultAction = 'login';
    private $authService;
    protected $loginView = 'login';

    public function __construct($id, $module, $config = [], AuthServiceInterface $authService)
    {
        parent::__construct($id, $module, $config);
        $this->authService = $authService;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verb' => Behavior::verb([
                'logout' => ['post'],
            ]),
        ];
    }

    public function actionLogin()
    {
        $form = new LoginForm();
        $body = Yii::$app->request->post();
        $isValid = $form->load($body) && $form->validate();
        if ($isValid) {
            try {

                $authForm = new AuthForm([
                    'login' => $form->login,
                    'password' => $form->password,
                    'rememberMe' => $form->rememberMe,
                ]);
                $this->authService->authByForm($authForm);
                
//                $this->authService->authenticationByForm($form);
                Toastr::create(['user', 'auth.login_success'], Toastr::TYPE_SUCCESS);
                return $this->goBack();
            } catch (UnprocessibleEntityException $e) {
                ErrorHelper::addErrorsFromException($e, $form);
            }
        }
        return $this->render($this->loginView, [
            'model' => $form,
        ]);
    }

    public function actionLogout($redirect = null)
    {
        $this->authService->logout();
        Toastr::create(['user', 'auth.logout_success'], Toastr::TYPE_SUCCESS);
        return $this->goHome();
        /*if ($redirect) {
            return $this->redirect([SL . $redirect]);
        } else {
            return $this->goHome();
        }*/
    }
}
