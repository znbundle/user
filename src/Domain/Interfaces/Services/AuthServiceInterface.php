<?php

namespace ZnBundle\User\Domain\Interfaces\Services;

use ZnCore\Contract\User\Exceptions\UnauthorizedException;
use ZnBundle\User\Domain\Forms\AuthForm;
use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;

interface AuthServiceInterface
{

    /**
     * @return IdentityEntityInterface
     * @throws UnauthorizedException
     */
    public function getIdentity(): ?IdentityEntityInterface;
    public function setIdentity(IdentityEntityInterface $identityEntity);
    //public function authenticationByForm(LoginForm $loginForm);
    public function authenticationByToken(string $token, string $authenticatorClassName = null);
    public function tokenByForm(AuthForm $form);

}
