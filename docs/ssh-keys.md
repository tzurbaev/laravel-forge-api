# Overview

SDK provides SSH Keys Manager that allows you to create, list and delete SSH keys on your Forge servers.

# Usage

Documentation assumes that you've already retrieved server instance from `Forge` collection.

All operations are performed via `Laravel\Forge\SshKeys\SshKeysManager` instance.

All methods return either instance of `Laravel\Forge\SshKeys\SshKey` or array of `Laravel\Forge\SshKeys\SshKey` instances.

## Create new SSH key

```php
<?php

use Laravel\Forge\SshKeys\SshKeysManager;

$keys = new SshKeysManager();

$key = $keys->create('key-name')->withContent('key-content')->on($server);
```

## List SSH keys

```php
<?php

use Laravel\Forge\SshKeys\SshKeysManager;

$keys = new SshKeysManager();

$serverKeys = $keys->list()->from($server);

foreach ($serverKeys as $key) {
  echo 'Key '.$key->name();
}
```

## Get single SSH key by ID

```php
<?php

use Laravel\Forge\SshKeys\SshKeysManager;

$keys = new SshKeysManager();

$keyId = 1234;
$key = $keys->get($keyId)->from($server);

echo 'Key '.$key->name();
```

## Delete SSH key

```php
<?php

use Laravel\Forge\SshKeys\SshKeysManager;

$keys = new SshKeysManager();

$key = $keys->get(1234)->from($server);

if ($key->delete()) {
  echo 'Key '.$name.' was deleted.';
}
```
