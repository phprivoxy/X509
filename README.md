# phprivoxy/x509
## Simple library for self-signed X509 certificate generation.

This PHP package will be useful for simple self-signed SLL certificate generation exclusively using only PHP (absolutely none OpenSSL console launches).

### Requirements 
- **PHP >= 8.1**

### Installation
#### Using composer (recommended)
```bash
composer phprivoxy/x509
```

### Manual certificate generation sample

```php
$rootNames = new PHPrivoxy\X509\DTO\Names('RU', 'PHP proxy', null, 'PHPrivoxy');
$rootCert = new PHPrivoxy\X509\DTO\Certificate($rootNames, ''ROOT_CA.crt');
$rootKey = new PHPrivoxy\X509\DTO\PrivateKey('ROOT_CA.key');
$rootCertCreator = new PHPrivoxy\X509\RootCertificateCreator($rootCert, $rootKey);
$rootCertCreator->getCertificate(); // It write CA certificate and it private key into it's files.

$names = new Names('RU', 'TEST', null, 'test');
$numberOfDays = 365; // One year.
$domains = ['*.test.local', 'www.test.local', 'test.local', 'test2.local', 'test3.local']; // Multidomains certificate.
$dns = new DNS($domains);
$key = new PrivateKey('self-signed-certificate.key');
$cert = new Certificate($names, 'self-signed-certificate.crt', $numberOfDays, $dns);

$certCreator = new CertificateCreator($cert, $key, $rootCert, $rootKey);

// It write certificate and it private key into it's files.
// Also returns PHPrivoxy\X509\DTO\Certificate object.
$certCreator->getCertificate();
```

### Dynamic certificate generation by host name sample

```php
$rootNames = new PHPrivoxy\X509\DTO\Names('RU', 'PHP proxy', null, 'PHPrivoxy');
$rootCertificate = new PHPrivoxy\X509\DTO\Certificate($rootNames, ''ROOT_CA.crt');
$rootKey = new PHPrivoxy\X509\DTO\PrivateKey('ROOT_CA.key');

$certificateDir = __DIR__ . 'certificates';
// If certificate directory (third argument) is null, certificate files don't be write (only generated).
$creator = new PHPrivoxy\X509\ServerCertificateCreator($rootCertificate, $rootKey, $certificateDir);

// Create files "certificates/test1.local.key" and "certificates/test1.local.crt".
$str1 = $creator->createCertificate('test1.local');

// Create files "certificates/test2.local.key" and "certificates/test2.local.crt".
$str2 = $creator->createCertificate('test2.local');

echo $str2; // Contains PHPrivoxy\X509\DTO\Certificate object.
print_r($str2);
```

Full samples you may find in "tests/create.php" and "tests/server.php" files - just run it:
```bash
php tests/create.php
```
```bash
php tests/server.php
```

Don't forget to add your generated self-signed CA certificate (ROOT_CA.crt in this samples) in trusted certificates!

### License
MIT License See [LICENSE.MD](LICENSE.MD)
