<?php

namespace PhpBundle\User\Yii\Api\controllers;

use PhpBundle\User\Domain\Forms\CreateAccountForm;
use PhpBundle\User\Domain\Forms\RequestCodeForm;
use PhpBundle\User\Domain\Services\RegistrationService;
use PhpBundle\User\Domain\Symfony\Authenticator;
use PhpBundle\User\Domain\Traits\AccessTrait;
use PhpLab\Core\Domain\Entities\Query\Where;
use PhpLab\Core\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Core\Domain\Helpers\QueryHelper;
use PhpLab\Core\Domain\Libs\DataProvider;
use PhpLab\Rest\Base\BaseCrudApiController;
use PhpLab\Rest\Libs\Serializer\JsonRestSerializer;
use PhpBundle\Messenger\Domain\Interfaces\ChatServiceInterface;
use PhpBundle\Messenger\Domain\Interfaces\Services\MessageServiceInterface;
use RocketLab\Bundle\Rest\Base\BaseCrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use yii\base\Module;

class RegistrationController extends BaseCrudController
{
    
    //use AccessTrait;

    private $registrationService;

    public function __construct(
        string $id,
        Module $module,
        array $config = [],
        RegistrationService $registrationService
    )
    {
        parent::__construct($id, $module, $config);
        $this->registrationService = $registrationService;
    }

    public function actionRequestActivationCode() {
        $post = \Yii::$app->request->post();
        $form = new RequestCodeForm;
        $form->phone = $post['phone'];
        $this->registrationService->requestActivationCode($form);
    }

    public function actionVerifyActivationCode() {
        return '';
        /*$post = \Yii::$app->request->post();
        $form = new RequestCodeForm;
        $form->phone = $post['phone'];
        $form->activation_code = $post['activation_code'];
        $this->registrationService->verifyActivationCode($form);*/
    }

    public function actionCreateAccount() {
        $post = \Yii::$app->request->post();
        $form = new CreateAccountForm;
        $form->phone = $post['phone'];
        $form->activation_code = $post['activation_code'];

        $this->registrationService->createAccount($form);
    }
}
