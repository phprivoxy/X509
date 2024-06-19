<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificatePropertiesInterface;
use PHPrivoxy\X509\DTO\PrivateKeyPropertiesInterface;
use PHPrivoxy\X509\OpenSSL\CertificateConfig;
use \OpenSSLCertificateSigningRequest;
use \OpenSSLAsymmetricKey;
use \OpenSSLCertificate;
use \Exception;

class CertificateCreator extends AbstractCertificateCreator
{
    protected PrivateKeyPropertiesInterface $rootKey;

    public function __construct(CertificatePropertiesInterface $certificate,
            PrivateKeyPropertiesInterface $key,
            CertificatePropertiesInterface $rootCertificate,
            PrivateKeyPropertiesInterface $rootKey)
    {
        $this->certificate = $certificate;
        $this->key = $key;
        $this->rootCertificate = $rootCertificate;
        $this->rootKey = $rootKey;
    }

    protected function getFromCSR(
            OpenSSLCertificateSigningRequest|false $csr,
            OpenSSLAsymmetricKey|false $privKey = false // TODO: Variable $privKey seems to be unused in its scope.
    ): OpenSSLCertificate|false
    {
        $sectionName = 'x509';
        $serialNumber = $this->generateSerialNumber();
        $altName = $this->certificate->subjectAltName();
        $config = (new CertificateConfig($altName))->getConfigContent($sectionName);
        $openSSLConfigFileName = $this->createConfig($config);
        $rootkeyPassword = $this->rootKey->privateKeyPassword();

        try {
            $options = $this->getOptions($openSSLConfigFileName, $sectionName);
            $ssCert = openssl_csr_sign($csr, $this->rootCertificatePEM, [$this->rootKeyPEM, $rootkeyPassword], $this->certificate->days(), $options, $serialNumber);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        } finally {
            @unlink($openSSLConfigFileName);
        }

        return $ssCert;
    }
}
