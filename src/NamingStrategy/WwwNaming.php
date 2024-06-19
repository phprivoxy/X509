<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

use PHPrivoxy\X509\DTO\DNS;

class WwwNaming extends AbstractNaming
{
    public function create(): void
    {
        $host1 = $this->host;
        if ('www.' <> substr($this->host, 0, 4)) {
            $host2 = 'www.' . $this->host;
        } else {
            $host2 = substr($this->host, 4);
        }

        $this->dns = new DNS([$host1, $host2]);
        $this->commonName = $this->host;
    }
}
