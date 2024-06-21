<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractCertificateProperties extends AbstractProperties implements CertificatePropertiesInterface
{
    protected int $defaultDays = 365;
    protected ?NamesInterface $names;
    protected ?string $certificateFile;
    protected ?int $days;
    protected ?DNSInterface $dns;

    public function names(): array
    {
        return $this->names->get();
    }

    public function certificateFile(): ?string
    {
        return $this->certificateFile;
    }

    public function days(): int
    {
        return $this->days;
    }

    public function subjectAltName(): ?string
    {
        return $this->dns->get();
    }
}
