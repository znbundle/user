<?php

namespace ZnBundle\User\Domain\Enums;

use yii2rails\extension\enum\base\BaseEnum;

class ConfirmActionEnum extends BaseEnum
{

    const REGISTRATION = 'registration';
	const RESTORE_PASSWORD = 'restore-password';

}