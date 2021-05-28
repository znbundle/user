<?php

return [
    'definitions' => [
        'ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface' => \ZnBundle\User\Domain\Entities\IdentityEntity::class,
    ],
    'singletons' => [
        'ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface' => 'ZnBundle\User\Domain\Services\JwtTokenService',
        'ZnBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface' => 'ZnBundle\User\Domain\Services\ConfirmService',
        'ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface' => 'ZnBundle\User\Domain\Services\AuthService3',
        'ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface' => 'ZnBundle\User\Domain\Services\IdentityService',
        'ZnBundle\User\Domain\Interfaces\Services\CredentialServiceInterface' => 'ZnBundle\User\Domain\Services\CredentialService',
        'ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository',
        'ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\CredentialRepository',
        'ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\SecurityRepository',
        'ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\ConfirmRepository',
        'ZnBundle\User\Domain\Interfaces\Repositories\TokenRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\TokenRepository',
    ],
    'entities' => [
        'ZnBundle\User\Domain\Entities\CredentialEntity' => 'ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface',
        'ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface' => 'ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface',
        'ZnBundle\User\Domain\Entities\TokenEntity' => 'ZnBundle\User\Domain\Interfaces\Repositories\TokenRepositoryInterface',
    ],
];