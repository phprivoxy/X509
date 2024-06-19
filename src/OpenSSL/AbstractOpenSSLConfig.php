<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\OpenSSL;

abstract class AbstractOpenSSLConfig implements OpenSSLConfigInterface
{
    protected ?string $config = null;

    abstract protected function createConfig(string $sectionName): void;
    //
    public function getConfigContent(string $sectionName): ?string
    {
        $this->prepare($sectionName);

        return $this->config;
    }

    private function prepare(string $sectionName): void
    {
        if (empty($sectionName)) {
            throw new OpenSSLConfigException('Config section name must be not empty.');
        }
        $this->createConfig($sectionName);
    }
}
