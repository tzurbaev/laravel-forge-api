# Overview

Servers collection holds all your Forge Servers in one place.

# Initialize Collection

You can start use collection by instantiating `Laravel\Forge\ForgeServers` class. Make sure you've created `Laravel\Forge\ApiProvider` instance - `ForgeServers` depends on it.

```php
<?php

use Laravel\Forge\ApiProvider;
use Laravel\Forge\ForgeServers;

$api = new ApiProvider('token');
$servers = new ForgeServers($api);
```

# Usage

Since `ForgeServers` implements `ArrayAccess` and `Iterator` interfaces, you have several methods to access your servers.

## Access existed servers

You can iterate through entire collection:

```php
<?php

foreach ($servers as $server) {
  // Each $server variable holds instance of `Laravel\Forge\Server` class.
  echo $server->name();
}
```

Or use it as array:

```php
<?php

$server = $servers['server-name'];
echo $server->name();
```

> Lazy Loading
> 
> ArrayAccess and Iterator implementations uses lazy loading.
> This means ForgeServers class won't load any data until you
> start iterating or performing array operations.

## Get single server

Additionally, `ForgeServers` class can be used to retrieve single server by ID:

```php
<?php

$serverId = 12345;
$server = $servers->get($serverId);
```

## Create new Server

Or create new server via fluent servers builder:

```php
<?php

$oceanCredentialId = 1234;

$droplet = $servers->create()
    ->droplet('my-droplet-name')
    ->usingCredential($oceanCredentialId)
    ->withMemoryOf('1GB')
    ->runningPhp('7.1')
    ->withMariaDb('my-database-name')
    ->save();
```

Code sample above will create new 1GB droplet on DigitalOcean with MariaDb and PHP 7.1. Also this will create database `my-database-name`.

[Back to Table of Contents](./readme.md)
