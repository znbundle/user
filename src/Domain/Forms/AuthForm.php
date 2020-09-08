<?php

namespace ZnBundle\User\Domain\Forms;

use ZnCore\Base\Helpers\ClassHelper;

class AuthForm
{

    public $login;
    public $password;

    public function __construct($data = null)
    {
        if($data) {
            ClassHelper::configure($this, $data);
        }
    }

}