<?php

namespace PhpBundle\User\Domain\Forms;

use PhpLab\Core\Helpers\ClassHelper;

class AuthForm
{

    public $login;
    public $password;

    public function __construct($data)
    {
        ClassHelper::configure($this, $data);
    }

}