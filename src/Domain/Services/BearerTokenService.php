<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Entities\CredentialEntity;
use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Repositories\CredentialRepositoryInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Legacy\Yii\Base\Security;
use ZnCore\Domain\Helpers\EntityHelper;

class BearerTokenService implements TokenServiceInterface
{

    private $credentialRepository;
    private $security;

    public function __construct(CredentialRepositoryInterface $credentialRepository, Security $security)
    {
        $this->credentialRepository = $credentialRepository;
        $this->security = $security;
    }

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenEntity
    {
        $token = $this->security->generateRandomString(32);
        try {
            $credentialEntity = $this->credentialRepository->oneByCredential($token, 'bearer');
        } catch (NotFoundException $exception) {
            $credentialEntity = new CredentialEntity();
            EntityHelper::setAttributes($credentialEntity, [
                'identity_id' => $identityEntity->getId(),
                'type' => 'bearer',
                'credential' => $token,
                'validation' => $token,
            ]);
            $this->credentialRepository->create($credentialEntity);
        }
        $tokenEntity = new TokenEntity($token, 'bearer', $identityEntity->getId());
        $tokenEntity->setId($credentialEntity->getId());
        return $tokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
        list($tokenType, $tokenValue) = explode(' ', $token);
        $credentialEntity = $this->credentialRepository->oneByCredential($tokenValue, 'bearer');
        return $credentialEntity->getIdentityId();
    }
}
