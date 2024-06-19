<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class ImmutableNames extends AbstractNames
{
    private array $names;

    public function __construct(
            ?string $countryName = null,
            ?string $stateOrProvinceName = null,
            ?string $localityName = null,
            ?string $organizationName = null,
            ?string $organizationalUnitName = null,
            ?string $commonName = null,
            ?string $emailAddress = null
    )
    {
        $this->setCountry($countryName);
        $this->setState($stateOrProvinceName);
        $this->setLocality($localityName);
        $this->setOrganization($organizationName);
        $this->setUnit($organizationalUnitName);
        $this->setCommon($commonName);
        $this->setEmail($emailAddress);
    }

    private function setCountry(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $this->countryName = $this->defaultCountry;
            return;
        } elseif (2 <> strlen($str)) {
            throw new DTOException('Incorrect country name.');
        }

        $this->countryName = strtoupper($str);
    }

    private function setState(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = $this->defaultState;
        }
        $this->stateOrProvinceName = $str;
    }

    private function setLocality(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->localityName = $str;
    }

    private function setOrganization(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = $this->defaultOrganization;
        }
        $this->organizationName = $str;
    }

    private function setUnit(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->organizationalUnitName = $str;
    }

    private function setCommon(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->commonName = $str;
    }

    private function setEmail(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $this->emailAddress = null;
            return;
        } elseif (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            throw new DTOException('Incorrect email address.');
        }
        $this->emailAddress = $str;
    }
}
