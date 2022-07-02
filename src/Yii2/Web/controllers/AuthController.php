<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use ZnBundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use ZnUser\Authentication\Domain\Forms\AuthForm;
use ZnUser\Authentication\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Base\Validation\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Entity\Helpers\EntityHelper;
use ZnLib\Rest\Yii2\Helpers\Behavior;
use ZnLib\Web\Yii2\Helpers\ErrorHelper;

class AuthController extends Controller
{

    public $defaultAction = 'login';
    private $authService;
    private $toastrService;
    protected $loginView = 'login';

    public function __construct(
        $id, $module, $config = [],
        AuthServiceInterface $authService,
        ToastrServiceInterface $toastrService)
    {
        parent::__construct($id, $module, $config);
        $this->authService = $authService;
        $this->toastrService = $toastrService;
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
                        'roles' => ['?', '@'],
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

        if(!$this->authService->isGuest()) {
            return $this->redirect('/');
        }

        $form = new LoginForm();
        $body = Yii::$app->request->post();
        $isValid = $form->load($body) && $form->validate();
        if ($isValid) {
            try {

                $authForm = new AuthForm();
                EntityHelper::setAttributes($authForm, [
                    'login' => $form->login,
                    'password' => $form->password,
                    'rememberMe' => $form->rememberMe,
                ]);
                $this->authService->authByForm($authForm);
                
//                $this->authService->authenticationByForm($form);
                $this->toastrService->success(['user', 'auth.login_success']);
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
        $this->toastrService->success(['user', 'auth.logout_success']);
        return $this->goHome();
        /*if ($redirect) {
            return $this->redirect([SL . $redirect]);
        } else {
            return $this->goHome();
        }*/
    }
}
