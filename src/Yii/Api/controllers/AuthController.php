<?php

namespace PhpBundle\User\Yii\Api\controllers;

use PhpBundle\User\Domain\Forms\AuthForm;
use PhpBundle\User\Domain\Services\AuthService;
use PhpBundle\User\Domain\Services\AuthService2;
use PhpBundle\User\Domain\Services\RegistrationService;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Enums\Http\HttpStatusCodeEnum;
use RocketLab\Bundle\Rest\Base\BaseController;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AuthController extends BaseController
{

    //use AccessTrait;

    private $authService;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        AuthService2 $authService
    )
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
                        'actions' => ['get-token'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['get-identity'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'get-token' => ['post'],
                    'get-identity' => ['get'],
                ],
            ],
        ];
    }

    public function actionGetToken()
    {
        $post = \Yii::$app->request->post();
        $form = new AuthForm();
        EntityHelper::setAttributes($form, $post);
        $tokenEntity = $this->authService->tokenByForm($form);
        \Yii::$app->response->headers->add('Authorization', $tokenEntity->getTokenString());
        \Yii::$app->response->setStatusCode(HttpStatusCodeEnum::NO_CONTENT);
        //return $tokenEntity->getTokenString();
    }

    public function actionGetIdentity()
    {
        return $this->authService->getIdentity();
    }
}
