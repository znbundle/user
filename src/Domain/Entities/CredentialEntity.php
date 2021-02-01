<?php

namespace ZnBundle\User\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

class CredentialEntity implements ValidateEntityByMetadataInterface, EntityIdInterface
{

    private $id = null;

    private $identityId = null;

    private $type = null;

    private $credential = null;

    private $validation = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('identityId', new Assert\NotBlank);
        $metadata->addPropertyConstraint('credential', new Assert\NotBlank);
        $metadata->addPropertyConstraint('validation', new Assert\NotBlank);
    }

    public function setId($value) : void
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setIdentityId($value) : void
    {
        $this->identityId = $value;
    }

    public function getIdentityId()
    {
        return $this->identityId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function setCredential($value) : void
    {
        $this->credential = $value;
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function setValidation($value) : void
    {
        $this->validation = $value;
    }

    public function getValidation()
    {
        return $this->validation;
    }


}

