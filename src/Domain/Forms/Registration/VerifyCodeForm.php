<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use Symfony\Component\Validator\Mapping\ClassMetadata;
ValidationByMetadataInterface
use ZnCore\Base\Enums\Http\HttpMethodEnum;
use Symfony\Component\Validator\Constraints as Assert;

class VerifyCodeForm extends RequestCodeForm implements ValidateEntityInterface
{

    protected $activationCode;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('activationCode', new Assert\NotBlank);
        $metadata->addPropertyConstraint('activationCode', new Assert\Positive);
        $metadata->addPropertyConstraint('activationCode', new Assert\Length(['value' => 6]));
    }

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function setActivationCode($activationCode): void
    {
        $this->activationCode = trim($activationCode);
    }

}
