<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\IdentityEntity;
use ZnBundle\User\Domain\Entities\SecurityEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\IdentityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\SecurityRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\IdentityServiceInterface;
use ZnCore\Base\Legacy\Yii\Base\Security;
use ZnCore\Domain\Base\BaseCrudService;
use ZnCore\Domain\Interfaces\Entity\EntityIdInterface;

class IdentityService extends BaseCrudService implements IdentityServiceInterface
{

    private $credentialRepository;

    public function __construct(IdentityRepositoryInterface $repository, CredentialRepositoryInterface $credentialRepository)
    {
        $this->setRepository($repository);
        $this->credentialRepository = $credentialRepository;
    }

    public function create($attributes): EntityIdInterface
    {
        $passwordHash = (new Security())->generatePasswordHash($attributes['password']);
        unset($attributes['password']);
        /** @var IdentityEntity $identityEntity */
        $identityEntity = parent::create($attributes);
        $credentialEntity = new CredentialEntity;
        $credentialEntity->setIdentityId($identityEntity->getId());
        $credentialEntity->setCredential($identityEntity->getLogin());
        $credentialEntity->setValidation($passwordHash);
        $this->credentialRepository->create($credentialEntity);
        return $identityEntity;
    }

    public function updateById($id, $data)
    {
        unset($data['password']);
        return parent::updateById($id, $data);
    }
}
