<?php

namespace ZnBundle\User\Domain\Entities;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ValidateEntityByMetadataInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * ORM\Column(type="string", unique=true, nullable=true)
     */
    private $apiToken;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('username', new Assert\NotBlank);
        $metadata->addPropertyConstraint('', new Assert\Length(['min' => 3]));
    }

    /**
     * Защита кода от показа на REST API
     *
     * @param bool $isShow
     * @return null|string
     */
    /*public function getPassword($isShow = false)
    {
        if( ! $isShow) {
            return null;
        }
        return $this->password;
    }*/

    /**
     * Защита кода от показа на REST API
     *
     * @param bool $isShow
     * @return null|string
     */
    /*public function getConfirmationToken($isShow = false)
    {
        if( ! $isShow) {
            return null;
        }
        return $this->confirmationToken;
    }*/

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * @param mixed $apiToken
     */
    public function setApiToken($apiToken): void
    {
        $this->apiToken = $apiToken;
    }
}