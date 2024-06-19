<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

class Names extends ImmutableNames implements ChangeableCommonNameInterface
{
    public function setCommonName(?string $str): void
    {
        if (null !== $str) {
            $str = trim($str);
        }
        if (empty($str)) {
            $str = null;
        }
        $this->commonName = $str;
    }
}
