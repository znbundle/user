<?php

namespace ZnBundle\User\Domain\Forms;

use ZnCore\Base\Helpers\ClassHelper;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AuthForm implements ValidateEntityInterface
{

    private $login;
    private $password;
    private $rememberMe = false;

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

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    
    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(bool $rememberMe): void
    {
        $this->rememberMe = $rememberMe;
    }
}