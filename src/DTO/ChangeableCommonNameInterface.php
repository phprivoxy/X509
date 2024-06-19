<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface ChangeableCommonNameInterface extends NamesInterface
{
    /*
     *  Set "CommonName" field in the Distinguished Name of certificate.
     */
    public function setCommonName(?string $str): void;
}
