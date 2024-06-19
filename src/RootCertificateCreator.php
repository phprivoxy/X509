<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificatePropertiesInterface;
use PHPrivoxy\X509\DTO\PrivateKeyPropertiesInterface;
use PHPrivoxy\X509\OpenSSL\RootCertificateConfig;
use \OpenSSLAsymmetricKey;
use \OpenSSLCertificate;
use \OpenSSLCertificateSigningRequest;
use \Exception;

class RootCertificateCreator extends AbstractCertificateCreator
{
    public function __construct(CertificatePropertiesInterface $certificate, PrivateKeyPropertiesInterface $key)
    {
        $this->certificate = $certificate;
        $this->key = $key;
    }

    protected function getFromCSR(
            OpenSSLCertificateSigningRequest|false $csr,
            OpenSSLAsymmetricKey|false $privKey
    ): OpenSSLCertificate|false
    {
        $sectionName = 'x509';
        $serialNumber = $this->generateSerialNumber();
        $config = (new RootCertificateConfig())->getConfigContent($sectionName);
        $openSSLConfigFileName = $this->createConfig($config);

        try {
            $options = $this->getOptions($openSSLConfigFileName, $sectionName);
            $ssCert = @openssl_csr_sign($csr, null, $privKey, $this->certificate->days(), $options, $serialNumber); // null - self-signed certificate
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        } finally {
            @unlink($openSSLConfigFileName);
        }

        return $ssCert;
    }
}
