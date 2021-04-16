# Laravel Forge API SDK

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/sardoj/laravel-forge-api/test?label=Tests)
[![StyleCI][ico-styleci]][link-styleci]
[![ScrutinizerCI][ico-scrutinizer]][link-scrutinizer]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

## Requirements
This package requires PHP 7.2 or higher.

## Installation

You can install the package via composer:

``` bash
$ composer require sardoj/laravel-forge-api
```

## Upgrade to 2.0 from 1.x

Version 2.0.0 introduced breaking change in library API in order to fix breaking change introduced by Forge API itself.

Please, read [Upgrade info](./docs/upgrade.md) to learn what changed.

## Examples

Here are few examples of what this package can do for you.

### Create new server

```php
<?php

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Forge;

$forge = new Forge(new ApiProvider('api-token'));
$credential = $forge->credentialFor('ocean2');

// This will create new droplet on DigitalOcean with 1GB memory,
// PHP 7.1 and MariaDb at Frankfurt region.
$server = $forge->create()
    ->droplet()
    ->usingCredential($credential)
    ->withSizeId(1)
    ->at('fra1')
    ->runningPhp('7.1')
    ->withMariaDb()
    ->save();
```

### Create new site

```php
<?php

use Laravel\Forge\Forge;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Sites\SitesManager;

$forge = new Forge(new ApiProvider('api-token'));
$server = $forge['web-01'];

// This will create new example.org site
// with General PHP/Laravel project type.
$site = (new SitesManager())->create('example.org')->asLaravel()->on($server);
```

### Install Git/WordPress application on site

```php
<?php

use Laravel\Forge\Forge;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Sites\SitesManager;
use Laravel\Forge\Applications\GitApplication;

$forge = new Forge(new ApiProvider('api-token'));
$server = $forge['web-01'];

$siteId = 1234;
$site = (new SitesManager())->get($siteId)->from($server);

$app = (new GitApplication())->fromGithub('username/repository');
$site->install($app);
```

### Restart MySQL

```php
<?php

use Laravel\Forge\Forge;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Services\MysqlService;
use Laravel\Forge\Services\ServicesManager;

$forge = new Forge(new ApiProvider('api-token'));

$databaseServer = $forge['database-01'];
$services = new ServicesManager();

$services->restart(new MysqlService())->on($databaseServer);
```

Or even restart MySQL (or any other service) on multiple servers:

```php
<?php

use Laravel\Forge\Forge;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Services\MysqlService;
use Laravel\Forge\Services\ServicesManager;

$forge = new Forge(new ApiProvider('api-token'));

$servers = [
    $forge['database-01'],
    $forge['database-02'],
    $forge['database-03'],
];

$services = new ServicesManager();
$services->restart(new MysqlService())->on($servers);
```

## Documentation

Full documentation is available [here](./docs/readme.md).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email zurbaev@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://poser.pugx.org/tzurbaev/laravel-forge-api/version?format=flat
[ico-license]: https://poser.pugx.org/tzurbaev/laravel-forge-api/license?format=flat
[ico-styleci]: https://styleci.io/repos/84751490/shield?branch=master&style=flat
[ico-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/laravel-forge-api/badges/quality-score.png?b=master

[link-packagist]: https://packagist.org/packages/tzurbaev/laravel-forge-api
[link-styleci]: https://styleci.io/repos/84751490
[link-scrutinizer]: https://scrutinizer-ci.com/g/tzurbaev/laravel-forge-api/
[link-author]: https://github.com/tzurbaev
