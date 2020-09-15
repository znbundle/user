<?php

namespace ZnBundle\User\Domain\Enums;

use ZnCore\Domain\Base\BaseEnum;

class ConfirmActionEnum extends BaseEnum
{

    const REGISTRATION = 'registration';
	const RESTORE_PASSWORD = 'restore-password';

}