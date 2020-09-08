<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountForm extends VerifyCodeForm
{

    private $username;
    private $email;
    private $password;

    public function validationRules(): array
    {
        $rules = parent::validationRules();
        return array_merge($rules, [
            'username' => [
                new Assert\NotBlank,
                new Assert\Regex(array(
                    'pattern' => '/^\w+$/',
                )),
            ],
            'email' => [
                new Assert\NotBlank,
                new Assert\Email,
            ],
            'password' => [
                new Assert\NotBlank,
                new Assert\Length(['min' => 6, 'max' => 18]),
            ],
        ]);
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
