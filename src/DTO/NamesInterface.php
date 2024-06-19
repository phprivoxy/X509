<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

interface NamesInterface
{
    /*
     *  Returns array with the Distinguished Name or subject fields to be used in the certificate.
     */
    public function get(): array;
}