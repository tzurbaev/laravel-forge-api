# Overview

SDK provides Certificates Manager that allows you to create, list, clone, install, activate, delete certificates and obtain new Let's Encrypt certificate for your Forge sites.

# Usage

Documentation assumes that you've already retrieved site instance from `SitesManager` class.

All operations are performed via `Laravel\Forge\Certificates\CertificatesManager` instance.

All methods return either instance of `Laravel\Forge\Certificates\Certificate` or array of `Laravel\Forge\Certificates\Certificate` instances.

## Create new certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$certificate = $certificates->create('example.org')
    ->ownedBy('My Company Name')
    ->locatedAt('US', 'NY', 'New York')
    ->assignedTo('Department Name')
    ->on($site);
```

## Installing an existed certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$certificate = $certificates->install('private-key', 'certificate-content')
    ->on($site);
```

## Cloning an existed certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$certificateId = 1234;
$certificate = $certificates->clone($certificateId)->on($site);
```

## Obtain a Let's Encrypt certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$domains = ['example.org', 'www.example.org'];
$certificate = $certificates->obtain($domains)->on($site);
```

## List certificates

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$siteCertificates = $certificates->list()->from($site);
```

## Get single certificate by ID

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();

$certificateId = 1234;
$certificate = $certificates->get($certificateId)->from($site);
```

## Get Signing Request

This method returns the full certificate signing request content.

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();
$certificate = $certificates->get(1234)->from($site);

$csr = $certificate->csr();
```

## Install certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();
$certificate = $certificates->get(1234)->from($site);

$certificateContent = 'content-here';
$addIntermediates = false;

$result = $certificate->install($certificateContent, $addIntermediates);
```

## Activate certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();
$certificate = $certificates->get(1234)->from($site);

$result = $certificate->activate();
```

## Delete certificate

```php
<?php

use Laravel\Forge\Certificates\CertificatesManager;

$certificates = new CertificatesManager();
$certificate = $certificates->get(1234)->from($site);

$result = $certificate->delete();
```

[Back to Table of Contents](./readme.md)
