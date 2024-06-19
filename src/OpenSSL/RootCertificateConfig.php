<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\OpenSSL;

class RootCertificateConfig extends AbstractOpenSSLConfig
{
    protected function createConfig(string $sectionName): void
    {
        $this->config = '[ ' . $sectionName . ' ]
subjectKeyIdentifier = hash
authorityKeyIdentifier = keyid:always,issuer
basicConstraints = critical,CA:TRUE
keyUsage = critical,digitalSignature,cRLSign,keyCertSign,nonRepudiation,keyEncipherment
extendedKeyUsage = TLS Web Server Authentication, TLS Web Client Authentication';
    }
}
