<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\NamingStrategy;

use PHPrivoxy\X509\DTO\DNSInterface;

abstract class AbstractNaming implements NamingInterface
{
    protected string $host;
    protected string $commonName;
    protected DNSInterface $dns;

    /*
     * Prepare Certificate properties ("commonName" and "dns").
     */
    public function prepare(string $host): void
    {
        $host = trim($host);
        if (empty($host)) {
            throw new NamingException('Host name must be not empty.');
        }
        $this->host = $host;
        $this->create();
    }

    abstract protected function create();

    /*
     * Returns Certificate "commonName" field after prepare() method calling.
     */
    public function commonName(): string
    {
        return $this->commonName;
    }

    /*
     * Returns Certificate hosts in DNSInterface format after prepare() method calling.
     */
    public function dns(): DNSInterface
    {
        return $this->dns;
    }
}
