<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class DNS extends AbstractDNS
{
    public function __construct(array $domains = [])
    {
        $this->domains = $domains;
        $this->prepare();
    }

    private function prepare(): void
    {
        if (!empty($this->dns) || empty($this->domains)) {
            return;
        }
        $dns = [];
        foreach ($this->domains as $domain) {
            if (!is_string($domain)) {
                continue;
            }
            $domain = trim($domain);
            if (empty($domain)) {
                continue;
            }
            $dns[] = 'DNS:' . $domain;
        }
        $this->dns = empty($dns) ? null : implode(',', $dns);
    }
}
