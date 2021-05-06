<?php

namespace ZnBundle\User\Domain\Enums;

class AuthEventEnum
{

    const BEFORE_AUTH = 'before_auth';
    const AFTER_AUTH_SUCCESS = 'after_auth_success';
    const AFTER_AUTH_ERROR = 'after_auth_error';

}
