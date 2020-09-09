<?php

namespace ZnBundle\User\Domain\Forms\Registration;

use ZnCore\Domain\Interfaces\Entity\ValidateEntityInterface;
use ZnCore\Base\Enums\Http\HttpMethodEnum;
use ZnCore\Base\Helpers\StringHelper;
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
