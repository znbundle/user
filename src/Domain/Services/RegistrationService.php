<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\Notify\Domain\Entities\SmsEntity;
use PhpBundle\Notify\Domain\Services\SmsService;
use PhpBundle\User\Domain\Forms\Registration\CreateAccountForm;
use PhpBundle\User\Domain\Forms\Registration\RequestCodeForm;
use PhpBundle\User\Domain\Forms\Registration\VerifyCodeForm;
use PhpLab\Core\Domain\Helpers\ValidationHelper;
use yii2bundle\account\domain\v3\helpers\ConfirmHelper;

class RegistrationService
{

    private $smsService;

    public function __construct(
        SmsService $smsService
    ) {
        $this->smsService = $smsService;
    }

    public function requestActivationCode(RequestCodeForm $requestCodeForm)
    {
        ValidationHelper::validateEntity($requestCodeForm);
        // todo: save to confirm table
        $smsEntity = new SmsEntity;
        $smsEntity->setPhone($requestCodeForm->getPhone());
        $code = ConfirmHelper::generateCode();
        $message = 'Код для регистрации: ' . $code;
        // todo: use i18next
        $smsEntity->setMessage($message);
        $this->smsService->push($smsEntity);
    }

    public function verifyActivationCode(VerifyCodeForm $requestCodeForm)
    {
        ValidationHelper::validateEntity($requestCodeForm);

    }

    public function createAccount(CreateAccountForm $accountForm)
    {
        ValidationHelper::validateEntity($accountForm);

    }

}