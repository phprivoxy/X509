<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificatePropertiesInterface;
use PHPrivoxy\X509\DTO\PrivateKeyPropertiesInterface;
use PHPrivoxy\X509\DTO\ChangeableCommonNameInterface;
use PHPrivoxy\X509\DTO\Names;
use PHPrivoxy\X509\DTO\CertificateProperties;
use PHPrivoxy\X509\DTO\PrivateKeyProperties;
use PHPrivoxy\X509\DTO\CertificateInterface;
use PHPrivoxy\X509\NamingStrategy\NamingInterface;
use PHPrivoxy\X509\NamingStrategy\NamingFactory;

class ServerCertificateCreator implements ServerCertificateCreatorInterface
{
    protected $defaultCertificateLifetime = 90;
    private CertificatePropertiesInterface $rootCertificate;
    private PrivateKeyPropertiesInterface $rootKey;
    private ?ChangeableCommonNameInterface $names;
    private ?NamingInterface $naming;
    private ?string $certificatesDirectory;

    public function __construct(
            CertificatePropertiesInterface $rootCertificate,
            PrivateKeyPropertiesInterface $rootKey,
            ?string $certificatesDirectory = null,
            ?ChangeableCommonNameInterface $names = null,
            ?NamingInterface $naming = null
    )
    {
        $this->rootCertificate = $rootCertificate;
        $this->rootKey = $rootKey;

        $this->certificatesDirectory = $this->sanitizeCertificateDirectory($certificatesDirectory);

        if (null === $names) {
            $names = new Names('XX', // countryName
                    'Unknown State', // stateOrProvinceName
                    'Unknown City', // localityName
                    'Unknown Organization', // organizationName
                    'Unknown Unit', // organizationalUnitName
                    null, // commonName
                    null); // emailAddress
        }
        $this->names = $names;

        if (null === $naming) {
            $naming = NamingFactory::getStrategy('WwwNaming');
        }
        $this->naming = $naming;
    }

    /*
     * Created self-signed certificate for $host and write it and it's key into files.
     */
    public function createCertificate(string $host, ?int $days = null): CertificateInterface
    {
        $host = $this->sanitizeHost($host);
        $days = $this->sanitizeDays($days);

        $privateKeyFile = $this->getPrivateKeyFile($host);
        $certificateFile = $this->getCertificateFile($host);

        $this->naming->prepare($host);
        $commonName = $this->sanitizeCommonName($this->naming->commonName());
        $this->names->setCommonName($commonName); // It is not mandatory, but let it be.
        $dns = $this->naming->dns();

        $privateKeyPassword = null;
        //$privateKeyBits = 2048;
        //$key = new PrivateKey($privateKeyFile, $privateKeyPassword, $privateKeyBits);
        $key = new PrivateKeyProperties($privateKeyFile, $privateKeyPassword);
        $certificate = new CertificateProperties($this->names, $certificateFile, $days, $dns);

        $certificateCreator = new CertificateCreator($certificate, $key, $this->rootCertificate, $this->rootKey);

        return $certificateCreator->getCertificate();
    }

    private function sanitizeCertificateDirectory(?string $certificatesDirectory): ?string
    {
        if (null === $certificatesDirectory) {
            return $certificatesDirectory;
        }

        $certificatesDirectory = trim($certificatesDirectory);
        if (empty($certificatesDirectory)) {
            return null;
        }

        $last = substr($certificatesDirectory, -1);
        if ('/' <> $last && '\\' <> $last) {
            $certificatesDirectory .= '/';
        }

        return $certificatesDirectory;
    }

    private function sanitizeHost(string $host): string
    {
        $host = trim($host);
        if (empty($host)) {
            throw new X509Exception('Host name must be not empty.');
        }

        return $host;
    }

    private function sanitizeDays(?int $days): int
    {
        if (null === $days) {
            $days = $this->defaultCertificateLifetime;
        }
        if (0 >= $days) {
            throw new X509Exception('Certificate Lidetime must be positive.');
        }

        return $days;
    }

    private function sanitizeCommonName(string $commonName): string
    {
        $commonName = trim($commonName);
        if (empty($commonName)) {
            throw new X509Exception('Host name must be not empty.');
        }
        if ('*.' === substr($commonName, 0, 2)) {
            $commonName = substr($commonName, 2);
        }

        return $commonName;
    }

    private function getPrivateKeyFile(string $host): ?string
    {
        if (empty($this->certificatesDirectory)) {
            return null;
        }

        return $this->certificatesDirectory . $host . '.key';
    }

    private function getCertificateFile(string $host): ?string
    {
        if (empty($this->certificatesDirectory)) {
            return null;
        }

        return $this->certificatesDirectory . $host . '.crt';
    }
}
