<?php

namespace ZnBundle\User\Domain\Forms;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;

class AuthForm implements ValidateEntityByMetadataInterface
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('login', new Assert\NotBlank);
        $metadata->addPropertyConstraint('password', new Assert\NotBlank);
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