<?php

/*
 * Create self-signed sertificate.
 * If certificate file already exist and valid, it was be read from file.
 * If self-signed CA certificate not exist it was be created.
 */

declare(strict_types=1);

namespace PHPrivoxy\X509;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPrivoxy\X509\DTO\Names;
use PHPrivoxy\X509\DTO\CertificateProperties;
use PHPrivoxy\X509\DTO\PrivateKeyProperties;
use PHPrivoxy\X509\DTO\DNS;

$rootCertificateDir = __DIR__ . '/CA/'; // In current test directory.
$rootPrivateKeyFile = $rootCertificateDir . 'PHPrivoxy.key';
$rootCertificateFile = $rootCertificateDir . 'PHPrivoxy_CA.crt';

$certificateDir = __DIR__ . '/created_certificates/'; // In current test directory.
$privateKeyFile = $certificateDir . 'self-signed-certificate.key';
$certificateFile = $certificateDir . 'self-signed-certificate.crt';

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
$privateKeyBits = 2048;

$rootCert = new CertificateProperties($rootNames, $rootCertificateFile, $numberOfDays);
$rootKey = new PrivateKeyProperties($rootPrivateKeyFile, $privateKeyPassword, $privateKeyBits);
$rootCertCreator = new RootCertificateCreator($rootCert, $rootKey);
$obj1 = $rootCertCreator->getCertificate(); // It write CA certificate and it private key into it's files.

$names = new Names('RU', 'TEST', null, 'test');
$domains = ['*.test.local', 'www.test.local', 'test.local', 'test2.local', 'test3.local']; // Multidomains certificate.
$dns = new DNS($domains);

//$privateKeyPassword = 'quwibird';
$privateKeyPassword = null;
$numberOfDays = 3652; // 10 years
$privateKeyBits = 2048;

$cert = new CertificateProperties($names, $certificateFile, $numberOfDays, $dns);
$key = new PrivateKeyProperties($privateKeyFile, $privateKeyPassword, $privateKeyBits);

$certCreator = new CertificateCreator($cert, $key, $rootCert, $rootKey);
$obj2 = $certCreator->getCertificate(); // It write certificate and it private key into it's files.
