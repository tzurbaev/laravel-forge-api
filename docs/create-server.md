# Overview

SDK provides fluent builders for all supported server providers. You can start building new server by calling `create` method on `Laravel\Forge\ForgeServers` instance.

# Server Providers

## DigitalOcean

Use `droplet` method to create new DigitalOcean droplet.

```php
<?php

use Laravel\Forge\ForgeServers;

$servers = new ForgeServers($api);

$droplet = $servers->create()
  ->droplet('my-droplet-name')
  ->usingCredential(1234)
  ->withMemoryOf('2GB')
  ->at('fra1')
  ->save();
```

## Linode

Use `linode` method to create new Linode server.

```php
<?php

use Laravel\Forge\ForgeServers;

$servers = new ForgeServers($api);

$node = $servers->create()
  ->linode('my-server-name')
  ->usingCredential(1234)
  ->withMemoryOf('2GB')
  ->at(1) // "1" region represents "Frankfurt"
  ->save();
```

## AWS

Use `aws` method to create new AWS instance.

```php
<?php

use Laravel\Forge\ForgeServers;

$servers = new ForgeServers($api);

$aws = $servers->create()
  ->aws('my-server-name')
  ->usingCredential(1234)
  ->withMemoryOf('2GB')
  ->at('us-west-1')
  ->save();
```

## Custom VPS

Use `custom` method to create new custom VPS server. You'll be required to provide public and private IP addresses for this server.

```php
<?php

use Laravel\Forge\ForgeServers;

$servers = new ForgeServers($api);

$vps = $servers->create()
  ->custom('my-server-name')
  ->usingPublicIp('93.184.216.34')
  ->usingPrivateIp('10.0.10.1')
  ->save();
```

# Shared methods

You can call additional methods on all providers.

## Provision as load balancer

Just call `asLoadBalancer()` on methods chain to indicate that new server should be provisioned as load balancer:

```php
<?php

$loadBalancer = $servers->create()
  ->droplet('balancer-01')
  ->usingCredential(1234)
  ->withMemoryOf('512MB')
  ->at('fra1')
  ->asLoadBalancer()
  ->save();
```

## Install MariaDb instead of MySQL

`withMariaDb` method allows you to install MariaDb server instead of MySQL. This method accepts `string $database = 'forge'` argument. You may use it to provide default database name.

```php
<?php

$droplet = $servers->create()
  ->droplet('web-01')
  ->usingCredential(1234)
  ->withMemoryOf('512MB')
  ->at('fra1')
  ->withMariaDb('my-database')
  ->save();
```

## Specify default MySQL database name

By default Forge will install MySQL server and create `forge` database. If you need to change DB name, use `withMysql` method on chain and provide database name as first argument.

```php
<?php

$droplet = $servers->create()
  ->droplet('web-01')
  ->usingCredential(1234)
  ->withMemoryOf('512MB')
  ->at('fra1')
  ->withMysql('my-database')
  ->save();
```

> Warning!
> 
> Calling `withMysql` method will reset any previous `withMariaDb` calls
> (and vice-versa). This means that if you call both methods,
> the most latest database will be installed on new server.

## Set new server size

You're required to set server size for all providers except `custom`. Use `withMemoryOf` method and pass available human readable size value for your server provider. List of possible `memory` values available [here](https://forge.laravel.com/api-documentation#server-sizes).

```php
<?php

$heavyBackendDroplet = $servers->create()
  ->droplet('heavy-backend-01')
  ->usingCredential(1234)
  ->withMemoryOf('32GB')
  ->at('fra1')
  ->save();
```

## Set new server region

With an addition to server size, you're required to provide new server region (unless you're creating Custom VPS). `at` method accepts valid region identifier for your server provider. List of possible `region` values available [here](https://forge.laravel.com/api-documentation#regions).

```php
<?php

$londonDroplet = $servers->create()
  ->droplet('web-01')
  ->usingCredential(1234)
  ->withMemoryOf('8GB')
  ->at('lon1')
  ->save();
```

## Choose PHP version

Forge allows to provision new servers with PHP 5.6, PHP 7.0 or PHP 7.1. You can specify PHP version by calling `runningPhp` method. Valid version arguments are:

- `php56` (or `56`, or `5.6` or `php5.6`);
- `php70` (or `70` or `7.0` or `php7.0`);
- `php71` (or `71` or `7.1` or `php7.1`).

```php
<?php

$legacyDroplet = $servers->create()
  ->droplet('web-01')
  ->usingCredential(1234)
  ->withMemoryOf('1GB')
  ->at('fra1')
  ->runningPhp('5.6')
  ->save();
```

## Connect server to another servers

If you need your new server to be connected to some existed servers, you should use `connectedTo` method. It accepts an array of server IDs that the new server should be able to connect to.

```php
<?php

// Our new droplet should be able to connect
// to database droplet and Redis cache droplet.
$serverIds = [
  $servers['database-01']->id(),
  $servers['redis-01']->id(),
];

$web = $servers->create()
  ->droplet('web-02')
  ->usingCredential(1234)
  ->withMemoryOf('1GB')
  ->at('fra1')
  ->runningPhp('7.1')
  ->connectedTo($serverIds)
  ->save();
```

> Heads Up!
> 
> Servers can be connected to each other only if they're belongs
> to the same server provider and the same region!

## Set public and/or private IP

When creating Custom VPS you're requried to provide public and private IP addresses for new server. `usingPublicIp` and `usingPrivateIp` methods allows you to do so.

```php
<?php

$vps = $servers->create()
  ->custom('web-01')
  ->runningPhp('7.1')
  ->usingPublicIp('93.184.216.34')
  ->usingPrivateIp('10.0.10.1')
  ->save();
```

[Back to Table of Contents](./readme.md)
