<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

use PHPrivoxy\X509\DTO\CertificateInterface;

interface CertificateCreatorInterface
{
    /*
     * Returns certificate string (and write it in file, if it not exists).
     */
    public function getCertificate(bool $writeToFile = true): CertificateInterface;
}
