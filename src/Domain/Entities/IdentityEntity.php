<?php

namespace ZnBundle\User\Domain\Entities;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ZnCore\Base\Enums\StatusEnum;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

class IdentityEntity implements ValidateEntityByMetadataInterface, EntityIdInterface, IdentityEntityInterface
{

    protected $id = null;
    protected $login = null;
    protected $roles = [];
    protected $status = StatusEnum::ENABLED;
    protected $createdAt = null;
    protected $updatedAt = null;
    protected $assignments = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

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

    public function getAssignments()
    {
        return $this->assignments;
    }

    public function setAssignments($assignments): void
    {
        if($assignments) {
            $this->roles = EntityHelper::getColumn($assignments, 'itemName');
        }
        $this->assignments = $assignments;
    }

}
