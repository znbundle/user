<?php

namespace ZnBundle\User\Domain\Services;

use ZnBundle\User\Domain\Entities\TokenEntity;
use ZnBundle\User\Domain\Interfaces\Repositories\TokenRepositoryInterface;
use ZnBundle\User\Domain\Entities\TokenValueEntity;
use ZnBundle\User\Domain\Interfaces\Entities\IdentityEntityInterface;
use ZnBundle\User\Domain\Interfaces\Services\TokenServiceInterface;
use ZnCore\Base\Exceptions\NotFoundException;
use ZnCore\Base\Legacy\Yii\Base\Security;

class BearerTokenService implements TokenServiceInterface
{

    private $tokenRepository;
    private $security;
    private $tokenLength = 64;

    public function __construct(TokenRepositoryInterface $tokenRepository, Security $security)
    {
        $this->tokenRepository = $tokenRepository;
        $this->security = $security;
    }

    public function getTokenLength(): int
    {
        return $this->tokenLength;
    }

    public function setTokenLength(int $tokenLength): void
    {
        $this->tokenLength = $tokenLength;
    }

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenValueEntity
    {
        $token = $this->security->generateRandomString($this->tokenLength);
        try {
            $tokenEntity = $this->tokenRepository->oneByValue($token, 'bearer');
        } catch (NotFoundException $exception) {
            $tokenEntity = new TokenEntity();
            $tokenEntity->setIdentityId($identityEntity->getId());
            $tokenEntity->setType('bearer');
            $tokenEntity->setValue($token);
            $this->tokenRepository->create($tokenEntity);
        }
        $resultTokenEntity = new TokenValueEntity($token, 'bearer', $identityEntity->getId());
        $resultTokenEntity->setId($tokenEntity->getId());
        return $resultTokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
        list($tokenType, $tokenValue) = explode(' ', $token);
        $tokenEntity = $this->tokenRepository->oneByValue($tokenValue, 'bearer');
        return $tokenEntity->getIdentityId();
    }
}
