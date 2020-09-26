<?php

namespace ZnBundle\User\Domain\Entities;

use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

class IdentityEntity implements ValidateEntityInterface, EntityIdInterface, IdentityEntityInterface
{

    protected $id = null;
    protected $login = null;
    protected $roles = [];
    protected $status = null;
    protected $createdAt = null;
    protected $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    public function validationRules()
    {
        return [];
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLogin($value)
    {
        $this->login = $value;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        if($roles) {
            $this->roles = $roles;
        }
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getLogo()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->getLogin()) . '?d=retro';
    }

    public function getUsername()
    {
        return $this->getLogin();
    }
}
