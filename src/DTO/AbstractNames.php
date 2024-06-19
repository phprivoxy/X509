<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractNames implements NamesInterface
{
    private array $names;
    protected string $defaultCountry = 'XX';
    protected string $defaultState = 'Some-State';
    protected string $defaultOrganization = 'Internet Widgits Pty Ltd';
    protected ?string $countryName;
    protected ?string $stateOrProvinceName;
    protected ?string $localityName;
    protected ?string $organizationName;
    protected ?string $organizationalUnitName;
    protected ?string $commonName;
    protected ?string $emailAddress;

    public function get(): array
    {
        $this->prepare();
        return $this->names;
    }

    private function prepare(): void
    {
        // If Certificate Distinguished Names consists empty fields
        // openssl_csr_new function throw error
        // "check error queue and value of string_mask OpenSSL option if illegal characters are reported".
        $fields = ['countryName', 'stateOrProvinceName', 'localityName',
            'organizationName', 'organizationalUnitName', 'commonName', 'emailAddress'];
        foreach ($fields as $field) {
            if (empty($this->$field)) {
                continue;
            }
            $this->names[$field] = $this->$field;
        }
    }
}
