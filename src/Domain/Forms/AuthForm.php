<?php

namespace ZnBundle\User\Domain\Forms;

use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AuthForm implements ValidateEntityInterface
{

    private $login;
    private $password;

    public function __construct($data = null)
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $value;
        }
    }

    public function validationRules()
    {
        return [
            'login' => [
                new Assert\NotBlank,
            ],
            'password' => [
                new Assert\NotBlank,
            ],
        ];
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login): void
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }
}