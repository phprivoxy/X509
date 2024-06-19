<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractPrivateKeyProperties implements PrivateKeyPropertiesInterface
{
    protected int $defaultPrivateKeyBits = 2048;
    protected ?string $privateKeyFile;
    protected ?string $privateKeyPassword;
    protected ?int $privateKeyBits;

    public function privateKeyFile(): ?string
    {
        return $this->privateKeyFile;
    }

    public function privateKeyPassword(): ?string
    {
        return $this->privateKeyPassword;
    }

    public function privateKeyBits(): int
    {
        return $this->privateKeyBits;
    }
}
