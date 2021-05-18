<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnCore\Domain\Interfaces\Service\CrudServiceInterface;

interface AuthServiceInterface //extends CrudServiceInterface
{

    /**
     * @return IdentityEntityInterface
     * @throws UnauthorizedException
     */
    public function getIdentity(): IdentityEntityInterface;
    public function setIdentity(IdentityEntityInterface $identityEntity);
    //public function authenticationByForm(LoginForm $loginForm);
    public function authenticationByToken(string $token, string $authenticatorClassName = null);
    public function tokenByForm(AuthForm $form);

}
