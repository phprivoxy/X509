<?php

declare(strict_types=1);

namespace PHPrivoxy\X509\DTO;

abstract class AbstractCertificateProperties implements CertificatePropertiesInterface
{
    protected int $defaultDays = 365;
    protected ?NamesInterface $names;
    protected ?string $certificateFile;
    protected ?int $days;
    protected ?DNSInterface $dns;

    public function names(): array
    {
        return $this->names->get();
    }

    public function certificateFile(): ?string
    {
        return $this->certificateFile;
    }

    public function days(): int
    {
        return $this->days;
    }

    public function subjectAltName(): ?string
    {
        return $this->dns->get();
    }

    /**
     * Normalize path
     * (from https://stackoverflow.com/questions/20522605/ )
     *
     * @param   string  $path
     * @param   string  $separator
     * @return  string  normalized path
     */
    protected function normalizePath(string $path): string
    {
        if (false !== strpos($path, '...')) {
            throw new DTOException('Incorrect path.');
        }
        $n = 0;
        $a = explode("/", preg_replace("/\/\.\//", '/', $path));
        $b = [];
        for ($i = sizeof($a) - 1; $i >= 0; --$i) {
            if (trim($a[$i]) === "..") {
                $n++;
            } else {
                if ($n > 0) {
                    $n--;
                } else {
                    $b[] = $a[$i];
                }
            }
        }
        if (1 >= count($b)) {
            throw new DTOException('Path is outside of the defined root.');
        }

        return implode("/", array_reverse($b));
    }
}
