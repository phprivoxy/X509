<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

use PHPrivoxy\X509\DTO\DNS;

class ImmutableNaming extends AbstractNaming
{
    public function create(): void
    {
        $this->dns = new DNS([$this->host]);
        $this->commonName = $this->host;
    }
}
