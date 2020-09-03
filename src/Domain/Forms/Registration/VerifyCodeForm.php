<?php

namespace PhpBundle\User\Domain\Forms\Registration;

use PhpLab\Core\Domain\Interfaces\Entity\ValidateEntityInterface;
use PhpLab\Core\Enums\Http\HttpMethodEnum;
use Symfony\Component\Validator\Constraints as Assert;

class VerifyCodeForm extends RequestCodeForm implements ValidateEntityInterface
{

    protected $activationCode;

    public function validationRules(): array
    {
        $rules = parent::validationRules();
        return array_merge($rules, [
            'activationCode' => [
                new Assert\NotBlank,
                new Assert\Positive,
                new Assert\Length(['value' => 6]),
            ],
        ]);
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
