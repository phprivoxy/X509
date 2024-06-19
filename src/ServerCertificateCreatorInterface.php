<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificateInterface;

interface ServerCertificateCreatorInterface
{
    /*
     * Create self-signed certificate for $host.
     */
    public function createCertificate(string $host, ?int $days = null): CertificateInterface;
}
