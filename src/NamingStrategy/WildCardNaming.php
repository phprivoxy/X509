<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

use PHPrivoxy\X509\DTO\DNS;

class WildCardNaming extends AbstractNaming
{
    public function create(): void
    {
        $host = $this->host;
        if ('www.' === substr($this->host, 0, 4)) {
            $host = substr($this->host, 4);
        }
        $wcHost = '*.' . $host;
        $this->dns = new DNS([$wcHost, $host]);
        $this->commonName = $wcHost;
    }
}
