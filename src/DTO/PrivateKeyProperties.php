<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class PrivateKeyProperties extends AbstractPrivateKeyProperties
{
    public function __construct(
            ?string $privateKeyFile = null,
            ?string $privateKeyPassword = null,
            ?int $privateKeyBits = null
    )
    {
        $this->setPrivateKeyFile($privateKeyFile);
        $this->setPrivateKeyPassword($privateKeyPassword);
        $this->setPrivateKeyBits($privateKeyBits);
    }

    private function setPrivateKeyFile(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->privateKeyFile = $str;
    }

    private function setPrivateKeyPassword(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->privateKeyPassword = $str;
    }

    private function setPrivateKeyBits(?int $int): void
    {
        if (null === $int) {
            $int = $this->defaultPrivateKeyBits;
        }
        if (2048 > $int) {
            throw new DTOException('Private key bits must be minimum 2048 bits.');
        }
        $this->privateKeyBits = $int;
    }
}
