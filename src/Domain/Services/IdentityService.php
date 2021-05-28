<?php

namespace ZnBundle\User\Domain\Services;

use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Enums\CredentialTypeEnum;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

/**
 * Class IdentityService
 * @package ZnBundle\User\Domain\Services
 * @method IdentityRepositoryInterface getRepository()
 */
class IdentityService extends BaseCrudService implements IdentityServiceInterface
{

    private $credentialRepository;
    private $passwordHasher;

    public function __construct(
        IdentityRepositoryInterface $repository,
        CredentialRepositoryInterface $credentialRepository,
        PasswordHasherInterface $passwordHasher
    )
    {
        $this->setRepository($repository);
        $this->credentialRepository = $credentialRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function create($attributes): EntityIdInterface
    {
        $passwordHash = $this->passwordHasher->hash($attributes['password']);
        unset($attributes['password']);
        /** @var IdentityEntityInterface $identityEntity */
        $identityEntity = parent::create($attributes);
        $credentialEntity = new CredentialEntity;
        $credentialEntity->setIdentityId($identityEntity->getId());
        $credentialEntity->setCredential($identityEntity->getLogin());
        $credentialEntity->setValidation($passwordHash);
        $credentialEntity->setType(CredentialTypeEnum::LOGIN);
        $this->credentialRepository->create($credentialEntity);
        return $identityEntity;
    }

    public function updateById($id, $data)
    {
        unset($data['password']);
        return parent::updateById($id, $data);
    }

    public function oneByUsername(string $username)
    {
        return $this->getRepository()->findUserByUsername($username);
    }
}
