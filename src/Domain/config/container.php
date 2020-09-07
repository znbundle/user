<?php

return [
    'PhpBundle\User\Domain\Interfaces\Services\IdentityServiceInterface' => 'PhpBundle\User\Domain\Services\IdentityService',
    'PhpBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface' => 'PhpBundle\User\Domain\Repositories\Eloquent\IdentityRepository',
    'PhpBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface' => 'PhpBundle\User\Domain\Repositories\Eloquent\SecurityRepository',
    'PhpBundle\\User\\Domain\\Interfaces\\Repositories\\ConfirmRepositoryInterface' => 'PhpBundle\\User\\Domain\\Repositories\\Eloquent\\ConfirmRepository',
    'PhpBundle\\Notify\\Domain\\Interfaces\\Services\\SmsServiceInterface' => 'PhpBundle\\Notify\\Domain\\Services\\SmsService',
];