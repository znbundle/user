<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Yii2\Forms\LoginForm;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;

interface AuthServiceInterface extends CrudServiceInterface
{

    public function authenticationByForm(LoginForm $loginForm);
    public function authenticationByToken(string $token, string $authenticatorClassName = null);
    public function tokenByForm(AuthForm $form);

}

