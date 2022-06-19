<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use ZnCore\Base\Libs\Text\Helpers\TextHelper;
use ZnCore\Domain\Interfaces\Entity\ValidateEntityByMetadataInterface;

class RequestCodeForm implements ValidateEntityByMetadataInterface
{

    protected $phone;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('phone', new Assert\NotBlank);
        $metadata->addPropertyConstraint('', new Assert\Regex(array(
            'pattern' => '/^77[\d+]{9}$/',
        )));
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = TextHelper::filterNumOnly($phone);
    }

}
