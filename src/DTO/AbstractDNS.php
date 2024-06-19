<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractDNS implements DNSInterface
{
    protected array $domains = [];
    protected ?string $dns = null;

    public function get(): ?string
    {
        return $this->dns;
    }
}
