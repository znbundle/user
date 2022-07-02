<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use ZnCore\Base\Develop\Helpers\DeprecateHelper;
use ZnCore\Base\Text\Helpers\TextHelper;
use ZnCore\Base\Validation\Interfaces\ValidationByMetadataInterface;

DeprecateHelper::hardThrow();

class RequestCodeForm implements ValidationByMetadataInterface
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
