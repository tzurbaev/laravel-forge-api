# Overview

SDK provides Services Manager class that allows you to install, uninstall, restart and stop some core services such as nginx, MySQL, Blackfire and others.

# Available services

By default SDK provides following services:

- MySQL/MariaDb service (as `Laravel\Forge\Services\MysqlService`);
- Nginx service (as `Laravel\Forge\Services\NginxService`);
- Postgres service (as `Laravel\Forge\Services\PostgresService`);
- Blackfire service (as `Laravel\Forge\Services\BlackfireService`);
- Papertrail service (as `Laravel\Forge\Services\PapertrailService`);

`Blackfire` and `Papertrail` services can be install and uninstalled (but not rebooted or stopped). Other services can be rebooted but can't be installed or uninstalled.

# Usage

> Documentation assumes that you've already retrieved
> server instance from `Forge` collection.
> 
> Also, you can perform operations on multiple servers at once
> (see "Excute commands on multiple servers" section of this page).

First of all, you need to instantiate `Laravel\Forge\Services\ServicesManager` class that provides operation methods.

```php
<?php

use Laravel\Forge\Services\ServicesManager;

$services = new ServicesManager();
```

## Install Blackfire service

Blackfire service requires `Server ID` and `Server Token` in order to be installed.

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\BlackfireService;

$services = new ServicesManager();

$result = $services->install(new BlackfireService())
  ->withPayload([
    'server_id' => 'blackfire-server-id',
    'server_token' => 'blackfire-server-token',
  ])
  ->on($server);
```

## Uninstall Blackfire service

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\BlackfireService;

$services = new ServicesManager();

$result = $services->uninstall(new BlackfireService())->from($server);
```

## Install Papertrail service

Papertrail service requires `host` in order to be installed.

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\PapertrailService;

$services = new ServicesManager();

$result = $services->install(new PapertrailService())
  ->withPayload([
    'host' => 'logs.papertrailapp.com:40000',
  ])
  ->on($server);
```

## Uninstall Papertrail service

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\PapertrailService;

$services = new ServicesManager();

$result = $services->uninstall(new PapertrailService())->from($server);
```

## Reboot Services

Only MySQL, nginx and Postgres services can be rebooted.

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\MysqlService;
use Laravel\Forge\Services\NginxService;
use Laravel\Forge\Services\PostgresService;

$services = new ServicesManager();

// Reboot MySQL/MariaDb.
$services->reboot(new MysqlService())->on($server);

// Reboot nginx.
$services->reboot(new NginxService())->on($server);

// Reboot Postgres.
$services->reboot(new PostgresService())->on($server);
```

## Stop services

Only MySQL, nginx and Postgres services can be stopped.

```php
<?php

use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Services\MysqlService;
use Laravel\Forge\Services\NginxService;
use Laravel\Forge\Services\PostgresService;

$services = new ServicesManager();

// Stop MySQL/MariaDb.
$services->stop(new MysqlService())->on($server);

// Stop nginx.
$services->stop(new NginxService())->on($server);

// Stop Postgres.
$services->stop(new PostgresService())->on($server);
```

# Excute commands on multiple servers

By default, documentation assumes that you're performing operations on single server (`$server` variable is an instance of `Laravel\Forge\Server` class). But you can pass array of `Laravel\Forge\Server` instances if you need to install/uninstall/reboot/stop services on multiple servers.

```php
<?php

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Forge;
use Laravel\Forge\Services\ServicesManager;

$api = new ApiProvider('forge-token');
$forge = new Forge($api);

$servers = [
  $forge['database-01'],
  $forge['database-02'],
  $forge['database-03'],
];

$services = new ServicesManager();

// Reboot MySQL on multiple servers.

$services->reboot(new MysqlService())->on($servers);
```

[Back to Table of Contents](./readme.md)
