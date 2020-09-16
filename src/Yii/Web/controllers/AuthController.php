<?php

namespace ZnBundle\User\Yii\Web\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use ZnBundle\User\Yii\Forms\LoginForm;
use yii2bundle\applicationTemplate\common\enums\ApplicationPermissionEnum;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnSandbox\Sandbox\Html\Yii2\Widgets\Toastr\widgets\Alert;
use ZnLib\Rest\Yii2\Helpers\Behavior;

/**
 * AuthController controller
 */
class AuthController extends Controller
{
    public $defaultAction = 'login';
    private $authService;

    public function __construct($id, $module, $config = [], AuthService2 $authService)
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
                if (!$this->isBackendAccessAllowed()) {
                    $this->authService->logout();
                    Alert::create(['user', 'auth.login_access_error'], Alert::TYPE_DANGER);
                    return $this->goHome();
                }
                Alert::create(['user', 'auth.login_success'], Alert::TYPE_SUCCESS);
                return $this->goBack();
            } catch (UnprocessibleEntityException $e) {
                $form->addErrorsFromException2($e);
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

    private function isBackendAccessAllowed()
    {
        if (APP != BACKEND) {
            return true;
        }
        if (Yii::$app->user->can(ApplicationPermissionEnum::BACKEND_ALL)) {
            return true;
        }
        return false;
    }

}
