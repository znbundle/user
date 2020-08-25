<?php

namespace PhpBundle\User\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use PhpLab\Core\Domain\Interfaces\Entity\ValidateEntityInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements ValidateEntityInterface
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

    public function validationRules(): array
    {
        return [
            'username' => [
                new Length(['min' => 3]),
                new NotBlank,
            ],
        ];
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