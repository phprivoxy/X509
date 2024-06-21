<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class Certificate extends AbstractCertificate
{
    public function __construct(
            string $publicKey,
            string $privateKey,
            ?string $chain,
            ?CertificatePropertiesInterface $properties,
            ?PrivateKeyPropertiesInterface $keyProperties
    )
    {
        // TODO: openssl_x509_verify($publicKey, $rootCertificate)
        // https://www.php.net/manual/ru/function.openssl-x509-verify.php
        $this->setPublicKey($publicKey);
        $this->setPrivateKey($privateKey);
        $this->setChain($chain);
        $this->setProperties($properties);
        $this->setKeyProperties($keyProperties);
    }

    public function setPublicKey(string $publicKey)
    {
        if (empty(trim($publicKey))) {
            throw new DTOException('Certificate public key must be not empty.');
        }
        $this->publicKey = $publicKey;
    }

    public function setPrivateKey(string $privateKey)
    {
        if (empty(trim($privateKey))) {
            throw new DTOException('Certificate private key must be not empty.');
        }
        $this->privateKey = $privateKey;
    }

    public function setChain(?string $chain)
    {
        $this->chain = $chain;
    }

    public function setProperties(?CertificatePropertiesInterface $properties)
    {
        $this->properties = $properties;
    }

    public function setKeyProperties(?PrivateKeyPropertiesInterface $keyProperties)
    {
        $this->keyProperties = $keyProperties;
    }
}
