<?php

namespace PhpBundle\User\Domain\Entities;

use PhpLab\Core\Enums\Measure\TimeEnum;
use PhpBundle\Crypt\Domain\Enums\EncryptAlgorithmEnum;
use PhpBundle\Crypt\Domain\Enums\EncryptFunctionEnum;
use PhpBundle\Jwt\Domain\Enums\JwtAlgorithmEnum;

class JwtProfileEntity
{

    protected $name;
    protected $lifeTime = TimeEnum::SECOND_PER_MINUTE * 20;
    // protected $allowed_algs = ['HS256', 'SHA512', 'HS384', 'RS256'];
    protected $allowedAlgs = [
        JwtAlgorithmEnum::HS256,
        JwtAlgorithmEnum::HS512,
        JwtAlgorithmEnum::HS384,
        JwtAlgorithmEnum::RS256,
    ];
    protected $defaultAlg = JwtAlgorithmEnum::HS256;
    protected $hashAlg = EncryptAlgorithmEnum::SHA256;
    protected $func = EncryptFunctionEnum::HASH_HMAC;
    protected $audience = [];
    protected $issuerUrl;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|int
     */
    public function getLifeTime()
    {
        return $this->lifeTime;
    }

    /**
     * @param float|int $lifeTime
     */
    public function setLifeTime($lifeTime): void
    {
        $this->lifeTime = $lifeTime;
    }

    /**
     * @return array
     */
    public function getAllowedAlgs(): array
    {
        return $this->allowedAlgs;
    }

    /**
     * @param array $allowedAlgs
     */
    public function setAllowedAlgs(array $allowedAlgs): void
    {
        $this->allowedAlgs = $allowedAlgs;
    }

    /**
     * @return string
     */
    public function getDefaultAlg(): string
    {
        return $this->defaultAlg;
    }

    /**
     * @param string $defaultAlg
     */
    public function setDefaultAlg(string $defaultAlg): void
    {
        $this->defaultAlg = $defaultAlg;
    }

    /**
     * @return string
     */
    public function getHashAlg(): string
    {
        return $this->hashAlg;
    }

    /**
     * @param string $hashAlg
     */
    public function setHashAlg(string $hashAlg): void
    {
        $this->hashAlg = $hashAlg;
    }

    /**
     * @return string
     */
    public function getFunc(): string
    {
        return $this->func;
    }

    /**
     * @param string $func
     */
    public function setFunc(string $func): void
    {
        $this->func = $func;
    }

    /**
     * @return array
     */
    public function getAudience(): array
    {
        return $this->audience;
    }

    /**
     * @param array $audience
     */
    public function setAudience(array $audience): void
    {
        $this->audience = $audience;
    }

    /**
     * @return mixed
     */
    public function getIssuerUrl()
    {
        return $this->issuerUrl;
    }

    /**
     * @param mixed $issuerUrl
     */
    public function setIssuerUrl($issuerUrl): void
    {
        $this->issuerUrl = $issuerUrl;
    }

}