<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\Notify\Domain\Entities\SmsEntity;
use PhpBundle\Notify\Domain\Services\SmsService;
use PhpBundle\User\Domain\Forms\CreateAccountForm;
use PhpBundle\User\Domain\Forms\RequestCodeForm;
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
        $smsEntity = new SmsEntity;
        $smsEntity->setPhone($requestCodeForm->phone);
        $code = ConfirmHelper::generateCode();
        $message = 'Код для регистрации: ' . $code;
        // todo: use i18next
        $smsEntity->setMessage($message);
        $this->smsService->push($smsEntity);
    }

    public function verifyActivationCode(RequestCodeForm $requestCodeForm)
    {

    }

    public function createAccount(CreateAccountForm $accountForm)
    {

    }

}