<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

use PHPrivoxy\X509\DTO\DNSInterface;

interface NamingInterface
{
    /*
     * Prepare Certificate properties ("commonName" and "dns").
     */
    public function prepare(string $host): void;

    /*
     * Returns Certificate "commonName" field after prepare() method calling.
     */
    public function commonName(): string;

    /*
     * Returns Certificate hosts in DNSInterface format after prepare() method calling.
     */
    public function dns(): DNSInterface;
}
