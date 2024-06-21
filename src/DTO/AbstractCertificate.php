<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractCertificate implements CertificateInterface
{
    protected string $publicKey;
    protected string $privateKey;
    protected ?string $chain;
    protected ?CertificatePropertiesInterface $properties;
    protected ?PrivateKeyPropertiesInterface $keyProperties;

    public function publicKey(): string
    {
        return $this->publicKey;
    }

    public function privateKey(): string
    {
        return $this->privateKey;
    }

    public function chain(): ?string
    {
        return $this->chain;
    }

    public function properties(): ?CertificatePropertiesInterface
    {
        return $this->properties;
    }

    public function keyProperties(): ?PrivateKeyPropertiesInterface
    {
        return $this->keyProperties;
    }
}
