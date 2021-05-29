<?php

namespace ZnBundle\User\Domain\Entities;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnCore\Base\Enums\StatusEnum;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;

class IdentityEntity implements ValidateEntityByMetadataInterface, EntityIdInterface, IdentityEntityInterface
{

    protected $id = null;
    protected $username = null;
    protected $statusId = StatusEnum::ENABLED;
    protected $createdAt = null;
    protected $updatedAt = null;

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

    public function setStatusId($value)
    {
        $this->statusId = $value;
    }

    public function getStatusId()
    {
        return $this->statusId;
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

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }
}
