<?php

namespace PhpBundle\User\Domain\Entities;

use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;
use Symfony\Component\Validator\Constraints as Assert;
use PhpLab\Core\Domain\Interfaces\Entity\ValidateEntityInterface;
use DateTime;

class ConfirmEntity implements ValidateEntityInterface, EntityIdInterface
{

    private $id = null;

    private $login = null;

    private $action = null;

    private $code = null;

    private $isActivated = false;

    private $data = null;

    private $expire = null;

    private $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTime;
    }

    public function validationRules()
    {
        return [
            'login' => [
                new Assert\NotBlank,
            ],
            'action' => [
                new Assert\NotBlank,
            ],
            'code' => [
                new Assert\NotBlank,
            ],
            /*'isActivated' => [
                new Assert\NotBlank,
            ],*/
            /*'data' => [
                new Assert\NotBlank,
            ],*/
            'expire' => [
                new Assert\NotBlank,
            ],
            'createdAt' => [
                new Assert\NotBlank,
            ],
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setLogin($value) : void
    {
        $this->login = $value;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setAction($value) : void
    {
        $this->action = $value;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setCode($value) : void
    {
        $this->code = $value;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setIsActivated(bool $value) : void
    {
        $this->isActivated = $value;
    }

    public function getIsActivated(): bool
    {
        return $this->isActivated;
    }

    public function setData($value) : void
    {
        $this->data = $value;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setExpire($value) : void
    {
        $this->expire = $value;
    }

    public function getExpire()
    {
        return $this->expire;
    }

    public function setCreatedAt($value) : void
    {
        $this->createdAt = $value;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

}
