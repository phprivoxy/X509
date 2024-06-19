<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\PrivateKeyPropertiesInterface;
use \Exception;

class PrivateKeyCreator implements PrivateKeyCreatorInterface
{
    private PrivateKeyPropertiesInterface $parameters;

    public function __construct(PrivateKeyPropertiesInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getPrivateKey(bool $writeToFile = true): string
    {
        if ($key = $this->readFromFile()) {
            return $key;
        }

        try {
            $privKey = openssl_pkey_new([
                "private_key_bits" => $this->parameters->privateKeyBits()
                    // , "private_key_type" => $this->parameters->privateKeyType()
            ]);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }

        try {
            openssl_pkey_export($privKey, $privateKey, $this->parameters->privateKeyPassword()); // $privatekey consist Private Key
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }

        if (empty(trim($privateKey))) {
            throw new X509Exception('Incorrect private key.');
        }

        if ($writeToFile) {
            $this->writeToFile($privateKey);
        }

        return $privateKey;
    }

    private function readFromFile(): string|false
    {
        if (empty($file = $this->parameters->privateKeyFile()) || !file_exists($file)) {
            return false;
        }

        try {
            $key = @file_get_contents($file);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }
        if (false === $key) {
            throw new X509Exception('Unable read private key file "' . $file . '".');
        }

        try {
            $privKey = openssl_pkey_get_private($key, $this->parameters->privateKeyPassword());
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }
        if (false === $privKey) {
            throw new X509Exception('Unable to prepare private key from file "' . $file . '".');
        }

        return $key;
    }

    private function writeToFile(string $str): void
    {
        if (empty($file = $this->parameters->privateKeyFile())) {
            return;
        }

        $certificateDir = dirname($file);
        if (!@is_dir($certificateDir)) {
            mkdir($certificateDir, 0755, true);
        }

        try {
            $result = @file_put_contents($file, $str);
        } catch (Exception $e) {
            throw new X509Exception($e->message());
        }

        if (false === $result) {
            throw new X509Exception('Unable write private key to file "' . $file . '".');
        }
    }
}
