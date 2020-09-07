<?php

namespace PhpBundle\User\Domain\Services;

use PhpBundle\Notify\Domain\Entities\SmsEntity;
use PhpBundle\Notify\Domain\Interfaces\Services\SmsServiceInterface;
use PhpBundle\Notify\Domain\Services\SmsService;
use PhpBundle\User\Domain\Entities\ConfirmEntity;
use PhpBundle\User\Domain\Enums\ConfirmActionEnum;
use PhpBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;
use PhpBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface;
use PhpLab\Core\Domain\Base\BaseCrudService;
use PhpLab\Core\Domain\Entities\Query\Where;
use PhpLab\Core\Domain\Libs\Query;
use PhpLab\Core\Exceptions\AlreadyExistsException;
use PhpLab\Core\Exceptions\NotFoundException;
use PhpLab\Core\Libs\I18Next\Facades\I18Next;
use yii2bundle\account\domain\v3\helpers\ConfirmHelper;

class ConfirmService extends BaseCrudService implements ConfirmServiceInterface
{

    private $smsService;

    public function __construct(ConfirmRepositoryInterface $repository, SmsServiceInterface $smsService)
    {
        $this->repository = $repository;
        $this->smsService = $smsService;
        $this->repository->deleteExpired();
    }

    public function isVerify(string $login, string $action, string $code): bool {
        $confirmEntity = $this->repository->oneByUnique($login, $action);
        return $code == $confirmEntity->getCode();
    }

    public function sendConfirmBySms(ConfirmEntity $confirmEntity, array $i18Next) {
        $this->checkExists($confirmEntity->getLogin(), $confirmEntity->getAction());
        $code = ConfirmHelper::generateCode();
        $confirmEntity->setCode($code);
        $this->persist($confirmEntity);
        $this->sendSmsWithCode($confirmEntity->getLogin(), $code, $i18Next);
    }

    private function checkExists(string $phone, string $action)
    {
        $isHas = $this->isHasByUnique($phone, $action);
        if ($isHas) {
            $timeLeft = $this->getTimeLeft($phone, $action);
            throw new AlreadyExistsException(strval($timeLeft));
        }
    }

    private function sendSmsWithCode(string $phone, string $code, array $i18Next)
    {
        $smsEntity = new SmsEntity;
        $smsEntity->setPhone($phone);
        $message = I18Next::t($i18Next[0], $i18Next[1], ['code' => $code]);
        $smsEntity->setMessage($message);
        $this->smsService->push($smsEntity);
    }

    private function isHasByUnique(string $login, string $action): bool
    {
        $query = new Query;
        $query->whereNew(new Where('login', $login));
        $query->whereNew(new Where('action', $action));
        $collection = $this->repository->all($query);
        return $collection->count() > 0;
    }

    private function getTimeLeft(string $login, string $action): int
    {
        $confirmEntity = $this->repository->oneByUnique($login, $action);
        return $confirmEntity->getExpire() - time();
    }

}
