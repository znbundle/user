<?php

namespace PhpBundle\User\Domain\Services;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Illuminate\Support\Collection;
use PhpBundle\Jwt\Domain\Entities\JwtEntity;
use PhpBundle\Crypt\Domain\Exceptions\InvalidPasswordException;
use PhpBundle\Jwt\Domain\Helpers\JwtHelper;
use PhpBundle\Jwt\Domain\Interfaces\Services\JwtServiceInterface;
use PhpBundle\Crypt\Domain\Interfaces\Services\PasswordServiceInterface;
use PhpBundle\Jwt\Domain\Repositories\Config\ProfileRepository;
use PhpBundle\Jwt\Domain\Services\JwtService;
use PhpBundle\User\Domain\Entities\User;
use PhpBundle\User\Domain\Exceptions\UnauthorizedException;
use PhpBundle\User\Domain\Forms\AuthForm;
use PhpLab\Core\Domain\Entities\ValidateErrorEntity;
use PhpLab\Core\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Core\Exceptions\NotFoundException;

class AuthService
{

    private $em;
    private $userManager;
    private $passwordService;
    private $jwtService;

    public function __construct(EntityManagerInterface $em, UserManagerInterface $userManager, JwtServiceInterface $jwtService, PasswordServiceInterface $passwordService)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->passwordService = $passwordService;
        $this->jwtService = $jwtService;
    }

    public function info(): UserInterface
    {
        /** @var User $userEntity */
        $userEntity = $this->userManager->findUserByUsername('user1');
        if (empty($userEntity)) {
            $exception = new UnauthorizedException;
            throw $exception;
        }
        return $userEntity;
    }

    public function authentication(AuthForm $form): UserInterface
    {
        /** @var User $userEntity */
        $userEntity = $this->userManager->findUserByUsername($form->login);
        if (empty($userEntity)) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
        $this->verificationPassword($userEntity, $form->password);
        $token = $this->forgeToken($userEntity);
        //$token = StringHelper::generateRandomString(64);
        $userEntity->setApiToken($token);
        return $userEntity;
    }

    public function authenticationByToken(string $token): UserInterface
    {
        /** @var User $userEntity */

        $token = explode(' ', $token)[1];

        $jwtEntity = $this->jwtService->verify($token, 'auth');
        $dto = $this->jwtService->decode($token);
        $userId = $dto->payload->subject->id;
        $userEntity = $this->userManager->findUserBy(['id' => $userId]);
        //dd($userEntity);
        
        //$userEntity = $this->userManager->findUserByUsername($form->login);
        if (empty($userEntity)) {
            throw new NotFoundException();
            /*$errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;*/
        }
        //$this->verificationPassword($userEntity, $form->password);
        //$token = $this->forgeToken($userEntity);
        //$token = StringHelper::generateRandomString(64);
        //$userEntity->setApiToken($token);
        return $userEntity;
    }

    private function verificationPassword(UserInterface $userEntity, string $password): bool
    {
        try {
            return $this->passwordService->validate($password, $userEntity->getPassword());
        } catch (InvalidPasswordException $e) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('password');
            $validateErrorEntity->setMessage('Bad password');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
    }

    private function forgeToken(UserInterface $userEntity)
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = ['id' => $userEntity->getId()];
        $token = 'jwt ' . $this->jwtService->sign($jwtEntity, 'auth');
        return $token;
    }
}