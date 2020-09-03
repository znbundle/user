<?php

namespace PhpBundle\User\Domain\Forms;

class CreateAccountForm extends RequestCodeForm
{

    public $phone;
    public $activation_code;

}
