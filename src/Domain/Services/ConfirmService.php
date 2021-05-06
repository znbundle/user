<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\Notify\Domain\Entities\SmsEntity;
use ZnBundle\Notify\Domain\Interfaces\Services\SmsServiceInterface;
use ZnBundle\User\Domain\Entities\ConfirmEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface;
use ZnBundle\User\Yii2\Helpers\ConfirmHelper;
use ZnCore\Base\Exceptions\AlreadyExistsException;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\Query\Where;
use ZnCore\Domain\Libs\Query;

class ConfirmService extends BaseCrudService implements ConfirmServiceInterface
{

    private $smsService;

    public function __construct(ConfirmRepositoryInterface $repository, SmsServiceInterface $smsService)
    {
        $this->setRepository($repository);
        $this->smsService = $smsService;
        $this->getRepository()->deleteExpired();
    }

    public function isVerify(string $login, string $action, string $code): bool
    {
        $confirmEntity = $this->getRepository()->oneByUnique($login, $action);
        return $code == $confirmEntity->getCode();
    }

    public function activate(string $login, string $action, string $code)
    {
        /** @var ConfirmEntity $confirmEntity */
        $confirmEntity = $this->getRepository()->oneByUnique($login, $action);
        $isValidCode = $code == $confirmEntity->getCode();
        if($isValidCode) {
            $confirmEntity->setIsActivated(true);
        } else {
            throw new \Exception('Activation code invalid!');
        }
    }

    public function add(ConfirmEntity $confirmEntity)
    {
        $this->checkExists($confirmEntity->getLogin(), $confirmEntity->getAction());
        $code = ConfirmHelper::generateCode();
        $confirmEntity->setCode($code);
        $this->persist($confirmEntity);
    }

    public function sendConfirmBySms(ConfirmEntity $confirmEntity, array $i18Next)
    {
        $this->add($confirmEntity);
        /*$this->checkExists($confirmEntity->getLogin(), $confirmEntity->getAction());
        $code = ConfirmHelper::generateCode();
        $confirmEntity->setCode($code);
        $this->persist($confirmEntity);*/
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
        $collection = $this->getRepository()->all($query);
        return $collection->count() > 0;
    }

    private function getTimeLeft(string $login, string $action): int
    {
        $confirmEntity = $this->getRepository()->oneByUnique($login, $action);
        return $confirmEntity->getExpire() - time();
    }
}
