<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificatePropertiesInterface;
use PHPrivoxy\X509\DTO\PrivateKeyPropertiesInterface;
use PHPrivoxy\X509\DTO\CertificateInterface;
use PHPrivoxy\X509\DTO\Certificate;
use \OpenSSLCertificateSigningRequest;
use \OpenSSLAsymmetricKey;
use \OpenSSLCertificate;
use \Exception;
use \DateTime;

abstract class AbstractCertificateCreator implements CertificateCreatorInterface
{
    protected CertificatePropertiesInterface $certificate;
    protected PrivateKeyPropertiesInterface $key;
    protected ?string $rootCertificatePEM = null;
    protected ?string $rootKeyPEM = null;

    abstract protected function getFromCSR(
            OpenSSLCertificateSigningRequest|false $csr,
            OpenSSLAsymmetricKey|false $privKey
    ): OpenSSLCertificate|false;

    /*
     * Returns certificate string (and write it in file, if it not exists).
     */
    public function getCertificate(bool $writeToFile = true): CertificateInterface
    {
        $createNewCertificate = false;
        $file = $this->key->privateKeyFile();
        if (null === $file || !file_exists($file)) {
            $createNewCertificate = true;
        }
        $privateKeyCreator = new PrivateKeyCreator($this->key);
        $privateKey = $privateKeyCreator->getPrivateKey();
        $password = $this->key->privateKeyPassword();

        // For not CA certificates.
        if (isset($this->rootCertificate) && isset($this->rootKey)) {
            $rootCertCreator = new RootCertificateCreator($this->rootCertificate, $this->rootKey);
            $rootCertificate = $rootCertCreator->getCertificate();
            $this->rootCertificatePEM = $rootCertificate->publicKey();
            $this->rootKeyPEM = $rootCertificate->privateKey();
        }

        if (false === $createNewCertificate) {
            $file = $this->certificate->certificateFile();
            if (file_exists($file)) {
                try {
                    $publicKey = @file_get_contents($file);
                    if ($this->checkCertificate($publicKey)) {
                        return new Certificate($publicKey, $privateKey, $this->rootCertificatePEM, $this->certificate);
                    }
                } catch (Exception $e) {// Not to do something.
                }
            }
        }

        try {
            $privKey = openssl_pkey_get_private($privateKey, $password);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }
        if (false === $privKey) {
            throw new X509Exception('Unable to create certificate asymmetric key.');
        }

        try {
            $csr = openssl_csr_new($this->certificate->names(), $privKey, null);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }
        if (false === $csr) {
            throw new X509Exception('Unable to create certificate signing request.');
        }

        $ssCert = $this->getFromCSR($csr, $privKey);
        if (false === $ssCert) {
            throw new X509Exception('Unable to sign certificate signing request.');
        }

        try {
            openssl_x509_export($ssCert, $publicKey); // $publicKey consist Public Key
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }
        if (empty(trim($publicKey))) {
            throw new X509Exception('Incorrect public key.');
        }

        if ($writeToFile) {
            $this->writeToFile($publicKey);
        }

        return new Certificate($publicKey, $privateKey, $this->rootCertificatePEM, $this->certificate);
    }

    private function writeToFile(string $str): void
    {
        if (empty($file = $this->certificate->certificateFile())) {
            return;
        }

        $certificateDir = dirname($file);
        if (!@is_dir($certificateDir)) {
            @mkdir($certificateDir, 0775, true);
        }

        $result = @file_put_contents($file, $str);

        if (false === $result) {
            throw new X509Exception('Unable write public key to file "' . $file . '".');
        }
    }

    protected function generateSerialNumber(): int
    {
// ((getrandmax() + 1) * getrandmax() * 2) + 4294967295
// (getrandmax() + 1) * getrandmax() * 2 + 1 + getrandmax() * 2
// (getrandmax() + 1) * (getrandmax() + getrandmax()) + 1 + getrandmax() + getrandmax()
        $max = getrandmax();
        $rnd1 = mt_rand(0, $max);
        $rnd2 = mt_rand(0, $max);
        $rnd3 = mt_rand(0, $max);
        $rnd4 = mt_rand(0, $max);
        $rnd5 = mt_rand(0, $max);

        return ($rnd1 + mt_rand(0, 1)) * ($rnd2 + $rnd3) + mt_rand(0, 1) + $rnd4 + $rnd5;
    }

    protected function getOptions(string $openSSLConfigFileName, string $sectionName): array
    {
        if (empty($openSSLConfigFileName)) {
            throw new X509Exception('OpenSSS config file name must be not empty.');
        }
        $options = [
            'config' => $openSSLConfigFileName,
            'x509_extensions' => $sectionName,
            'digest_alg' => 'sha256'
        ];

        return $options;
    }

    /*
     * Returns created config full file name.
     */
    protected function createConfig(string $config): string|false
    {
        $tmpDir = sys_get_temp_dir() . '/PHPrivoxy/TMP/';
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0755, true);
        }
        do {
            $fileName = $tmpDir . $this->getRandomString();
        } while (false === ($fp = @fopen($fileName, 'w+b')));

        $result = @fwrite($fp, $config);
        @fclose($fp);

        return false === $result ? false : $fileName;
    }

    private function getRandomString(): string
    {
        $random = sha1(random_bytes(40) . uniqid(microtime(), true));

        return base_convert($random, 16, 36);
    }

    private function checkCertificate(?string $certificate): bool
    {
        if (false === ($info = @openssl_x509_parse($certificate))) {
            return false;
        }
        if (!isset($info['validFrom_time_t']) || !isset($info['validTo_time_t'])) {
            return false;
        }

        $now = new DateTime();
        $from = (new DateTime())->setTimestamp($info['validFrom_time_t']);
        $to = (new DateTime())->setTimestamp($info['validTo_time_t']);

        return $from <= $now && $now < $to;
    }
}
