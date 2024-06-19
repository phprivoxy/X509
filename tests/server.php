<?php

/*
 * Create self-signed sertificate by host name.
 * If certificate file already exist and valid, it was be read from file.
 * If self-signed CA certificate not exist it was be created.
 */

declare(strict_types=1);

namespace PHPrivoxy\X509;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPrivoxy\X509\DTO\Names;
use PHPrivoxy\X509\DTO\CertificateProperties;
use PHPrivoxy\X509\DTO\PrivateKeyProperties;

$rootCertificateDir = __DIR__ . '/CA/'; // In current test directory.
$rootPrivateKeyFile = $rootCertificateDir . 'PHPrivoxy.key';
$rootCertificateFile = $rootCertificateDir . 'PHPrivoxy_CA.crt';

$certificateDir = __DIR__ . '/server_certificates/'; // In current test directory.

$rootNames = new Names(
        'RU', // countryName
        'PHP proxy', // stateOrProvinceName
        null, // localityName
        'PHPrivoxy', // organizationName
        null, // organizationalUnitName
        null, // commonName
        null // emailAddress
);

//$privKeyPassword = 'swordfish';
$privateKeyPassword = null;
$numberOfDays = 365241; // 1000 years
$privateKeyBits = 2048; // Must be minimum 2048 bits.

$rootCertificate = new CertificateProperties($rootNames, $rootCertificateFile, $numberOfDays);
$rootKey = new PrivateKeyProperties($rootPrivateKeyFile, $privateKeyPassword, $privateKeyBits);

// If certificate directory (third argument) is null, certificate files don't be write (only generated).
$creator = new ServerCertificateCreator($rootCertificate, $rootKey, $certificateDir);
$str1 = $creator->createCertificate('test1.local');
$str2 = $creator->createCertificate('test2.local');

//print_r($str1);
print_r($str2);
