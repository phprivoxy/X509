<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface DNSInterface
{
    /*
     * Returns Certificate Subject Alt Names string, if exists.
     */
    public function get(): ?string;
}
