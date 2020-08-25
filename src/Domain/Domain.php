<?php

namespace PhpBundle\User\Domain;

use PhpLab\Core\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'user';
    }

}