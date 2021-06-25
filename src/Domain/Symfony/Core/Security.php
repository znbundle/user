<?php

namespace ZnBundle\User\Domain\Symfony\Core;

use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Security extends \Symfony\Component\Security\Core\Security
{

    private $token;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->setToken(new NullToken());
    }

    public function setToken(TokenInterface $token): void
    {
        $this->token = $token;
    }

    public function getToken(): ?TokenInterface
    {
        return $this->token;
    }
}
