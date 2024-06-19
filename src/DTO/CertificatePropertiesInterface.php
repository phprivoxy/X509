<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface CertificatePropertiesInterface
{
    /*
     * Returns Certificate Distinguished Names.
     */
    public function names(): array;

    /*
     * Returns full Certificate File path.
     */
    public function certificateFile(): ?string;

    /*
     * Returns Certificate Lifetime.
     */
    public function days(): int;

    /*
     * Returns Certificate DNS (Subject Alt Names object), if exists.
     */
    public function subjectAltName(): ?string;
}
