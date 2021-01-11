<?php

namespace ZnBundle\User\Domain\Entities;

use Symfony\Component\Validator\Constraints as Assert;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

class CredentialEntity implements ValidateEntityInterface, EntityIdInterface
{

    private $id = null;

    private $identityId = null;

    private $type = null;

    private $credential = null;

    private $validation = null;

    public function validationRules()
    {
        return [
            /*'id' => [
                new Assert\NotBlank,
            ],*/
            'identityId' => [
                new Assert\NotBlank,
            ],
            'credential' => [
                new Assert\NotBlank,
            ],
            'validation' => [
                new Assert\NotBlank,
            ],
        ];
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

