<?php

namespace PhpBundle\User\Domain\Forms\Registration;

use PhpLab\Core\Domain\Interfaces\Entity\ValidateEntityInterface;
use PhpLab\Core\Enums\Http\HttpMethodEnum;
use PhpLab\Core\Helpers\StringHelper;
use Symfony\Component\Validator\Constraints as Assert;

class RequestCodeForm implements ValidateEntityInterface
{

    protected $phone;

    public function validationRules(): array
    {
        return [
            'phone' => [
                new Assert\NotBlank,
                new Assert\Regex(array(
                    'pattern' => '/^77[\d+]{9}$/',
                )),
            ],
        ];
    }
    
    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = StringHelper::filterNumOnly($phone);
    }

}
