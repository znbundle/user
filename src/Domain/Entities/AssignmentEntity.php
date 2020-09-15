<?php

namespace ZnBundle\User\Domain\Entities;

class AssignmentEntity
{

    private $itemName;
    private $userId;
    private $createdAt;

    public function getItemName()
    {
        return $this->itemName;
    }

    public function setItemName($itemName): void
    {
        $this->itemName = $itemName;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
