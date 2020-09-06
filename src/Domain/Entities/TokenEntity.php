<?php

namespace PhpBundle\User\Domain\Entities;

class TokenEntity
{

    private $token;
    private $type;
    private $identity_id;
    private $identity;

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getIdentityId()
    {
        return $this->identity_id;
    }

    public function setIdentityId($identity_id): void
    {
        $this->identity_id = $identity_id;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function setIdentity($identity): void
    {
        $this->identity = $identity;
    }

    public function getTokenString()
    {
        if (empty($this->token)) {
            return null;
        }
        if (empty($this->type)) {
            return $this->token;
        }
        return $this->type . SPC . $this->token;
    }

}
