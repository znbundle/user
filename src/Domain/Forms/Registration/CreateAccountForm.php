<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Base\Develop\Helpers\DeprecateHelper;

DeprecateHelper::hardThrow();

class CreateAccountForm extends VerifyCodeForm
{

    private $username;
    private $email;
    private $password;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Assert\NotBlank);
        $metadata->addPropertyConstraint('username', new Assert\Regex(array(
            'pattern' => '/^\w+$/',
        )));
        $metadata->addPropertyConstraint('email', new Assert\NotBlank);
        $metadata->addPropertyConstraint('email', new Assert\Email);
        $metadata->addPropertyConstraint('password', new Assert\NotBlank);
        $metadata->addPropertyConstraint('password', new Assert\Length(['min' => 6, 'max' => 18]));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = trim($username);
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = trim($email);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = trim($password);
    }

}
