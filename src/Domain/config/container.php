<?php

\ZnCore\Base\Helpers\DeprecateHelper::softThrow();

return [
    \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface::class => \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage::class,
    'ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface' => 'ZnBundle\User\Domain\Services\JwtTokenService',
    'ZnBundle\User\Domain\Interfaces\Services\ConfirmServiceInterface' => 'ZnBundle\User\Domain\Services\ConfirmService',
    'ZnBundle\User\Domain\Interfaces\Services\AuthServiceInterface' => 'ZnBundle\User\Domain\Services\AuthService3',
    'ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface' => 'ZnBundle\User\Domain\Services\IdentityService',
    'ZnBundle\User\Domain\Interfaces\Services\CredentialServiceInterface' => 'ZnBundle\User\Domain\Services\CredentialService',
    'ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\IdentityRepository',
    'ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\CredentialRepository',
    'ZnBundle\User\Domain\Interfaces\Repositories\ConfirmRepositoryInterface' => 'ZnBundle\User\Domain\Repositories\Eloquent\ConfirmRepository',
];