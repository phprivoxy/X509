<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\OpenSSL;

use PHPrivoxy\X509\DTO\DNSInterface;

class CertificateConfig extends AbstractOpenSSLConfig
{
    private string $altName;

    public function __construct(DNSInterface|string $altName)
    {
        if (!is_string($altName)) {
            $altName = $altName->get();
        }
        $this->altName = $altName;
    }

    protected function createConfig(string $sectionName): void
    {
        $this->config = '[ ' . $sectionName . ' ]
        subjectKeyIdentifier = hash
        authorityKeyIdentifier = keyid, issuer
        basicConstraints = critical, CA:FALSE
        keyUsage = critical, digitalSignature
        extendedKeyUsage = TLS Web Server Authentication, TLS Web Client Authentication';

        if (!empty($this->altName)) {
            $this->config .= chr(10) . 'subjectAltName = "' . $this->altName . '"';
        }
    }
}
