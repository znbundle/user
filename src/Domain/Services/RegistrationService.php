<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\Notify\Domain\Services\SmsService;
use PhpBundle\User\Domain\Entities\ConfirmEntity;
use PhpBundle\User\Domain\Enums\ConfirmActionEnum;
use PhpBundle\User\Domain\Forms\Registration\CreateAccountForm;
use PhpBundle\User\Domain\Forms\Registration\RequestCodeForm;
use PhpBundle\User\Domain\Forms\Registration\VerifyCodeForm;
use PhpLab\Core\Domain\Helpers\ValidationHelper;
use PhpLab\Core\Enums\Measure\TimeEnum;
use PhpLab\Core\Exceptions\AlreadyExistsException;
use PhpLab\Core\Exceptions\NotFoundException;
use PhpLab\Core\Libs\I18Next\Facades\I18Next;

class RegistrationService
{

    private $confirmService;

    public function __construct(
        ConfirmService $confirmService
    )
    {
        $this->confirmService = $confirmService;
    }

    public function requestActivationCode(RequestCodeForm $requestCodeForm)
    {
        ValidationHelper::validateEntity($requestCodeForm);
        $phone = $requestCodeForm->getPhone();
        $confirmEntity = new ConfirmEntity;
        $confirmEntity->setLogin($phone);
        $confirmEntity->setAction(ConfirmActionEnum::REGISTRATION);
        $confirmEntity->setExpire(time() + TimeEnum::SECOND_PER_MINUTE * 5);
        try {
            $this->confirmService->sendConfirmBySms($confirmEntity, ['user', 'registration.activate_account_sms']);
        } catch (AlreadyExistsException $e) {
            $message = I18Next::t('user', 'registration.user_already_exists_but_not_activation_time_left', ['timeLeft' => $e->getMessage()]);
            throw new AlreadyExistsException($message);
        }
    }

    public function verifyActivationCode(VerifyCodeForm $requestCodeForm)
    {
        ValidationHelper::validateEntity($requestCodeForm);
        try {
            $isVerify = $this->confirmService->isVerify($requestCodeForm->getPhone(), ConfirmActionEnum::REGISTRATION, $requestCodeForm->getActivationCode());
            if(! $isVerify) {
                $message = I18Next::t('user', 'registration.invalid_activation_code');
                ValidationHelper::throwUnprocessable(['activation_code' => $message]);
            }
        } catch (NotFoundException $e) {
            $message = I18Next::t('user', 'registration.temp_user_not_found');
            ValidationHelper::throwUnprocessable(['phone' => $message]);
        }
    }

    public function createAccount(CreateAccountForm $accountForm)
    {
        ValidationHelper::validateEntity($accountForm);

    }

}