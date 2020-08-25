<?php

namespace PhpBundle\User\Domain\Traits;

use PhpBundle\User\Domain\Exceptions\UnauthorizedException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

trait UserAwareTrait
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage = null;

    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getUser(): UserInterface
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ( ! $user instanceof UserInterface) {
            throw new UnauthorizedException;
        }
        return $user;
    }

}