<?php

return [
    'ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface' => 'ZnBundle\User\Domain\Services\IdentityService',
    'ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository',
    'ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\SecurityRepository',
    'ZnBundle\\User\\Domain\\Interfaces\\Repositories\\ConfirmRepositoryInterface' => 'ZnBundle\\User\\Domain\\Repositories\\Eloquent\\ConfirmRepository',
];