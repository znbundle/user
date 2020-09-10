<?php

namespace ZnBundle\User\Yii\Api\controllers;

use Illuminate\Support\Collection;
use ZnBundle\User\Domain\Forms\Registration\CreateAccountForm;
use ZnBundle\User\Domain\Forms\Registration\RequestCodeForm;
use ZnBundle\User\Domain\Forms\Registration\VerifyCodeForm;
use ZnBundle\User\Domain\Services\RegistrationService;
use ZnCore\Domain\Entities\ValidateErrorEntity;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnCore\Base\Exceptions\AlreadyExistsException;
use ZnLib\Rest\Yii2\Base\BaseController;
use yii\base\Module;
use Yii;

class RegistrationController extends BaseController
{

    //use AccessTrait;

    private $registrationService;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        RegistrationService $authService
    ) {
        parent::__construct($id, $module, $config);
        $this->registrationService = $authService;
    }

    public function actionRequestActivationCode()
    {
        $post = Yii::$app->request->post();
        $form = new RequestCodeForm;
        EntityHelper::setAttributes($form, $post);
        try {
            $this->registrationService->requestActivationCode($form);
            Yii::$app->response->setStatusCode(201);
        } catch (AlreadyExistsException $e) {
            Yii::$app->response->setStatusCode(202);
            return ['message' => $e->getMessage()];
            //ValidationHelper::throwUnprocessable(['phone' => $e->getMessage()]);
        }
    }

    public function actionVerifyActivationCode()
    {
        $post = Yii::$app->request->post();
        $form = new VerifyCodeForm;
        EntityHelper::setAttributes($form, $post);
        $this->registrationService->verifyActivationCode($form);
        Yii::$app->response->setStatusCode(204);
    }

    public function actionCreateAccount()
    {
        $post = Yii::$app->request->post();
        $form = new CreateAccountForm;
        EntityHelper::setAttributes($form, $post);
        $this->registrationService->createAccount($form);
        Yii::$app->response->setStatusCode(201);
    }
}
