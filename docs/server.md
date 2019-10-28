# Overview

`Laravel\Forge\Server` class allows you to perform some management operations on your server. Also, `Server` instance is required for most of Forge API commands (you'll learn it later).

# Usage

All examples below assuming that you've already created `ApiProvider` and `Forge` classes and retrieved (or created new) server via API.

## Getters

With getter methods you can retrieve server ID, name, size, PHP version, check if server is ready (was already provisioned) and some more.

```php
<?php

$server = $servers['my-server'];

$id = $server->id(); // Forge Server ID
$credentialId = $server->credentialId(); // Credential ID
$name = $server->name();
$size = $server->size(); // 1GB, 512MB, etc.
$region = $server->region(); // Frankfurt
$phpVersion = $server->phpVersion(); // php56, php70 or php71
$publicIp = $server->ip();
$privateIp = $server->privateIp();
$blackfireStatus = $server->blackfireStatus();
$papertrailStatus = $server->papertrailStatus();
$provider = $server->provider();
$providerId = $server->providerId();
$sshPort = $server->sshPort();
$tags = $server->tags();
$isRevoked = $server->isRevoked();
$isReady = $server->isReady();
$connectedServerIds = $server->network();

// The following getters are available on new server instances only.
$sudoPassword = $server->sudoPassword();
$databasePassword = $server->databasePassword();
```

## Update server data

Use `update` method and pass array of fields to be updated.

```php
<?php

$server->update([
  'name' => 'new-name',
  'size' => '512MB',
  'ip_address' => '192.241.143.108',
]);
```

> Note on size/IP changes
>
> IP and RAM changes do not affect your server,
> they only update Forge's knowledge about your server.

## Reboot server

Use `reboot` method to reboot your server.

```php
<?php

$server->reboot();
```

## Enable PHP OPCache server

Use `enableOPCache` method to enable PHP OPCache on your server.

```php
<?php

$server->enableOPCache();
```

## Disable PHP OPCache server

Use `disableOPCache` method to disable PHP OPCache on your server.

```php
<?php

$server->disableOPCache();
```

## Revoke Forge access to server

```php
<?php

$server->revokeAccess();
```

## Reconnect revoked server

This method will return an SSH key which you will need to add to the server. Once the key has been added to the server, you may "reactivate" it.

```php
<?php

$sshKey = $server->reconnect();
```

## Reactivate revoked server

Make sure you've installed an SSH key returned from `reconnect` method.

```php
<?php

$server->reactivate();
```

[Back to Table of Contents](./readme.md)
