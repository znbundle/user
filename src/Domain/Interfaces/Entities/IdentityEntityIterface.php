<?php

namespace PhpBundle\User\Domain\Interfaces\Entities;

interface IdentityEntityIterface
{

    public function setId($value);

    public function getId();

    public function setLogin($value);

    public function getLogin();
}
