<?php

namespace PhpBundle\User\Yii\Api\controllers;

use PhpBundle\User\Domain\Forms\Registration\CreateAccountForm;
use PhpBundle\User\Domain\Forms\Registration\RequestCodeForm;
use PhpBundle\User\Domain\Forms\Registration\VerifyCodeForm;
use PhpBundle\User\Domain\Services\RegistrationService;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use RocketLab\Bundle\Rest\Base\BaseController;
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
        $this->registrationService->requestActivationCode($form);
    }

    public function actionVerifyActivationCode()
    {
        $post = Yii::$app->request->post();
        $form = new VerifyCodeForm;
        EntityHelper::setAttributes($form, $post);
        $this->registrationService->verifyActivationCode($form);
    }

    public function actionCreateAccount()
    {
        $post = Yii::$app->request->post();
        $form = new CreateAccountForm;
        EntityHelper::setAttributes($form, $post);
        $this->registrationService->createAccount($form);
    }
}
