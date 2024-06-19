<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface PrivateKeyPropertiesInterface
{
    /*
     * Returns full Private Key File path.
     */
    public function privateKeyFile(): ?string;

    /*
     * Returns Private Key Password (may be null).
     */
    public function privateKeyPassword(): ?string;

    /*
     * Returns Private Key Bits.
     */
    public function privateKeyBits(): int;
}
