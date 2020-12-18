<?php

namespace ZnBundle\User\Yii2\Web\controllers;

use common\enums\rbac\ApplicationPermissionEnum;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnLib\Web\Yii2\Helpers\ErrorHelper;
use ZnLib\Web\Yii2\Widgets\Toastr\widgets\Alert;
use ZnLib\Rest\Yii2\Helpers\Behavior;

/**
 * AuthController controller
 */
class AuthController extends Controller
{
    public $defaultAction = 'login';
    private $authService;

    public function __construct($id, $module, $config = [], AuthServiceInterface $authService)
    {
        parent::__construct($id, $module, $config);
        $this->authService = $authService;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['logout', 'get-token'],
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

    /**
     * Logs in a user.
     */
    public function actionLogin()
    {
        $form = new LoginForm();
        $body = Yii::$app->request->post();
        $isValid = $form->load($body) && $form->validate();
        if ($isValid) {
            try {
                $this->authService->authenticationByForm($form);
                Alert::create(['user', 'auth.login_success'], Alert::TYPE_SUCCESS);
                return $this->goBack();
            } catch (UnprocessibleEntityException $e) {
                ErrorHelper::addErrorsFromException($e, $form);
            }
        }

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     */
    public function actionLogout($redirect = null)
    {
        $this->authService->logout();
        Alert::create(['user', 'auth.logout_success'], Alert::TYPE_SUCCESS);
        if ($redirect) {
            return $this->redirect([SL . $redirect]);
        } else {
            return $this->goHome();
        }
    }
}
