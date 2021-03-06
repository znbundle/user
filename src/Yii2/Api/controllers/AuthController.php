<?php

namespace ZnBundle\User\Yii2\Api\controllers;

use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Services\AuthService2;
use ZnCore\Base\Enums\Http\HttpStatusCodeEnum;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnLib\Rest\Yii2\Base\BaseController;

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
