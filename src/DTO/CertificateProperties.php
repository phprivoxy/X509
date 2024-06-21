<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class CertificateProperties extends AbstractCertificateProperties
{
    public function __construct(
            ?NamesInterface $names = null,
            ?string $certificateFile = null,
            ?int $days = null,
            ?DNSInterface $dns = null
    )
    {
        $this->setNames($names);
        $this->setCertificateFile($certificateFile);
        $this->setDays($days);
        $this->setDNS($dns);
    }

    private function setNames(?NamesInterface $names): void
    {
        if (null === $names) {
            $names = new Names();
        }
        $this->names = $names;
    }

    private function setCertificateFile(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->certificateFile = $this->normalizePath($str);
    }

    private function setDays(?int $int): void
    {
        if (null === $int) {
            $int = $this->defaultDays;
        }
        if (0 >= $int) {
            throw new Exception('Certificate livetime must be greater then zero.');
        }
        $this->days = $int;
    }

    private function setDNS(?DNSInterface $dns): void
    {
        if (null === $dns) {
            $dns = new DNS();
        }
        $this->dns = $dns;
    }
}
