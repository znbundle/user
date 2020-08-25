<?php

namespace PhpBundle\User\Domain\Entities;

use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Identity implements UserInterface, EntityIdInterface
{

    private $id;
    private $username;
    private $usernameCanonical;
    private $email;
    private $emailCanonical;
    private $enabled;
    private $salt;
    private $password;
    private $lastLogin;
    private $confirmationToken;
    private $passwordRequestedAt;
    private $roles;
    private $logo;

    public function eraseCredentials()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    public function setUsernameCanonical($usernameCanonical): void
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    public function setEmailCanonical($emailCanonical): void
    {
        $this->emailCanonical = $emailCanonical;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getSalt()
    {
        //return $this->salt;
    }

    public function setSalt($salt): void
    {
        $this->salt = $salt;
    }

    public function getPassword()
    {
        //return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt($passwordRequestedAt): void
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getRolesArray()
    {
        return unserialize($this->roles);
    }

    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getLogo()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->getEmail()) . '?d=retro';
    }

    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }

}