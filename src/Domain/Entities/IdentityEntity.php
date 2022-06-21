<?php

namespace ZnBundle\User\Domain\Entities;

use Illuminate\Support\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Base\Libs\Entity\Helpers\CollectionHelper;
use ZnCore\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use ZnCore\Base\Enums\StatusEnum;
use ZnCore\Base\Libs\Entity\Helpers\EntityHelper;
use ZnCore\Base\Libs\Entity\Interfaces\EntityIdInterface;
use ZnCore\Base\Libs\Validation\Interfaces\ValidationByMetadataInterface;
use DateTime;

class IdentityEntity implements ValidationByMetadataInterface, EntityIdInterface, IdentityEntityInterface, UserInterface
{

    protected $id = null;
    protected $username = null;
    protected $statusId = StatusEnum::ENABLED;
    protected $createdAt = null;
    protected $updatedAt = null;
    protected $roles = [];
    protected $assignments = null;

    public function __construct()
    {
        $this->createdAt = new DateTime;
        $this->updatedAt = new DateTime;
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

    public function getUpdatedAt(): ?DateTime
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
    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getAssignments(): ?Collection
    {
        return $this->assignments;
    }

    public function setAssignments(?Collection $assignments): void
    {
        $this->assignments = $assignments;
        if($assignments) {
            $this->roles = CollectionHelper::getColumn($assignments, 'itemName');
        }
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
