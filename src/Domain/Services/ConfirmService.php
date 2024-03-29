<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\Notify\Domain\Entities\SmsEntity;
use ZnBundle\Notify\Domain\Interfaces\Services\SmsServiceInterface;
use ZnBundle\User\Domain\Entities\ConfirmEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface;
use ZnCore\Base\Exceptions\AlreadyExistsException;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Libs\I18Next\Facades\I18Next;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Entities\Query\Where;
use ZnCore\Domain\Interfaces\Libs\EntityManagerInterface;
use ZnCore\Domain\Libs\Query;

/**
 * @method ConfirmRepositoryInterface getRepository
 */
class ConfirmService extends BaseCrudService implements ConfirmServiceInterface
{

    private $smsService;
    private $lengthCode = 6;

    public function generateCode()
    {
        $min = '1' . str_repeat('0', $this->lengthCode - 2) . '1';
        $max = str_repeat('9', $this->lengthCode);
        return rand($min, $max);
    }

    public function __construct(EntityManagerInterface $em, ConfirmRepositoryInterface $repository, SmsServiceInterface $smsService)
    {
        $this->setEntityManager($em);
        $this->setRepository($repository);
        $this->smsService = $smsService;
        $this->getRepository()->deleteExpired();
    }

    public function getEntityClass(): string
    {
        return ConfirmEntity::class;
    }

    public function isVerify(string $login, string $action, string $code): bool
    {
        try {
            $confirmEntity = $this->oneByLoginAction($login, $action);
        } catch (NotFoundException $e) {
            throw new NotFoundException(I18Next::t('user', 'confirm.not_found'));
        }
        return $code == $confirmEntity->getCode();
    }

    public function activate(string $login, string $action, string $code)
    {
        /** @var ConfirmEntity $confirmEntity */
        $confirmEntity = $this->oneByLoginAction($login, $action);
        $isValidCode = $code == $confirmEntity->getCode();
        if($isValidCode) {
            $confirmEntity->setIsActivated(true);
        } else {
            throw new \Exception('Activation code invalid!');
        }
        $this->getRepository()->deleteById($confirmEntity->getId());
//        $this->getEntityManager()->persist($confirmEntity);
    }

    public function add(ConfirmEntity $confirmEntity)
    {
        $this->checkExists($confirmEntity->getLogin(), $confirmEntity->getAction());
        $code = $this->generateCode();
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

    protected function oneByLoginAction(string $login, string $action): ConfirmEntity
    {
        /** @var ConfirmEntity $confirmEntity */
        $confirmEntity = $this->getRepository()->oneByUniqueAttributes($login, $action);
        $lifeTime = $confirmEntity->getExpire() - time();
        if($lifeTime <= 0) {
            $this->getRepository()->deleteById($confirmEntity->getId());
            throw new NotFoundException();
        }
        return $confirmEntity;
    }

//    protected function oneByUnique(string $login, string $action): ConfirmEntity
//    {
//        /** @var ConfirmEntity $confirmEntity */
//        $confirmEntity = $this->getRepository()->oneByUniqueAttributes($login, $action);
//        $lifeTime = $confirmEntity->getExpire() - time();
//        if($lifeTime <= 0) {
//            $this->getRepository()->deleteById($confirmEntity->getId());
//            throw new NotFoundException();
//        }
//        return $confirmEntity;
//    }

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
        $confirmEntity = $this->getRepository()->oneByUniqueAttributes($login, $action);
        return $confirmEntity->getExpire() - time();
    }
}
