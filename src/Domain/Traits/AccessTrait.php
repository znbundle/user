<?php

namespace PhpBundle\User\Domain\Traits;

use http\Env\Request;
use PhpBundle\User\Domain\Symfony\Authenticator;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

trait AccessTrait
{

    /** @var Authenticator */
    protected $authenticator;
    
    protected function checkAuth()
    {
        $user = $this->authenticator->getUser();
        if ( ! is_object($user) || ! $user instanceof UserInterface) {
            throw new UnauthorizedHttpException('Unauthorized');
        }
    }

}