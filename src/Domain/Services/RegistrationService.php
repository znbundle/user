<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\Notify\Domain\Entities\SmsEntity;
use PhpBundle\Notify\Domain\Services\SmsService;
use PhpBundle\User\Domain\Entities\ConfirmEntity;
use PhpBundle\User\Domain\Enums\ConfirmActionEnum;
use PhpBundle\User\Domain\Forms\Registration\CreateAccountForm;
use PhpBundle\User\Domain\Forms\Registration\RequestCodeForm;
use PhpBundle\User\Domain\Forms\Registration\VerifyCodeForm;
use PhpLab\Core\Domain\Helpers\ValidationHelper;
use PhpLab\Core\Enums\Measure\TimeEnum;
use PhpLab\Core\Libs\I18Next\Facades\I18Next;
use yii2bundle\account\domain\v3\helpers\ConfirmHelper;

class RegistrationService
{

    private $smsService;
    private $confirmService;

    public function __construct(
        SmsService $smsService,
        ConfirmService $confirmService
    )
    {
        $this->smsService = $smsService;
        $this->confirmService = $confirmService;
    }

    public function requestActivationCode(RequestCodeForm $requestCodeForm)
    {
        ValidationHelper::validateEntity($requestCodeForm);
        // todo: save to confirm table
        $code = ConfirmHelper::generateCode();
        $phone = $requestCodeForm->getPhone();
        $this->createConfirm($phone, $code);
        $this->sendSmsWithCode($phone, $code);
    }

    private function createConfirm(string $phone, string $code)
    {
        $confirmEntity = new ConfirmEntity;
        $confirmEntity->setLogin($phone);
        $confirmEntity->setAction(ConfirmActionEnum::REGISTRATION);
        $confirmEntity->setCode($code);
        $confirmEntity->setExpire(time() + TimeEnum::SECOND_PER_MINUTE * 5);
        $this->confirmService->persist($confirmEntity);
    }

    private function sendSmsWithCode(string $phone, string $code)
    {
        $smsEntity = new SmsEntity;
        $smsEntity->setPhone($phone);
        $message = I18Next::t('user', 'registration.activate_account_sms', ['code' => $code]);
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