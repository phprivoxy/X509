<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface CertificateInterface
{
    /*
     * Returns certificate public key.
     */
    public function publicKey(): string;

    /*
     * Returns certificate privave key.
     */
    public function privateKey(): string;

    /*
     * Returns certificate chain
     * (certificates what are issued by Certificate Authorities (CAs),
     * trusted entities responsible for issuing, etc).
     */
    public function chain(): ?string;

    /*
     * Returns certificate properties (if defined).
     */
    public function properties(): ?CertificatePropertiesInterface;
}
