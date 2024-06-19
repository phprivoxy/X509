<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\OpenSSL;

interface OpenSSLConfigInterface
{
    public function getConfigContent(string $sectionName): ?string;
}
