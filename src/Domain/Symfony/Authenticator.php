<?php

namespace PhpBundle\User\Domain\Symfony;

use PhpBundle\User\Domain\Entities\User;
use PhpBundle\User\Domain\Services\AuthService;
use PhpLab\Core\Enums\Http\HttpHeaderEnum;
use PhpLab\Core\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class Authenticator
{

    private $security;
    private $authService;
    
    public function __construct(Security $security, AuthService $authService)
    {
        $this->security = $security;
        $this->authService = $authService;
    }

    public function getUser(Request $request = null) {
        if($this->isAuthenticated()) {
            $userEntity = $this->security->getToken()->getUser();
        } else {
            $request = $request ?: Request::createFromGlobals();
            $token = $request->query->get(HttpHeaderEnum::AUTHORIZATION) OR $request->request->get(HttpHeaderEnum::AUTHORIZATION);
            if(empty($token)) {
                throw new UnauthorizedHttpException('Empty token!');
            }
            try {
                $userEntity = $this->authService->authenticationByToken($token);
                $this->security->getToken()->setUser($userEntity);
            } catch (NotFoundException $e) {
                throw new UnauthorizedHttpException('User not found!');
            }
        }
        //dd($this->security->getToken()->getUser());

        //dd($user);
        return $userEntity;
        
        /*$user = new User;
        $user->setId(123);
        $user->setUsername('asdfg');
        $security->getToken()->setUser($user);
        dd($security->getToken()->getUser());*/
    }

    public function isAuthenticated() {
        return $this->security->getToken()->getUser() instanceof UserInterface;
    }
}