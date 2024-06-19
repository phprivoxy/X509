<?php

declare(strict_types=1);

namespace PHPrivoxy\X509;

interface PrivateKeyCreatorInterface
{
    public function getPrivateKey(bool $writeToFile = true): string;
}
