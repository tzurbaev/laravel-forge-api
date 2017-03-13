# Overview

With addition to MySQL databases management, SDK provides command to create, list, update or delete MySQL users.

# Usage

Documentation assumes that you've already retrieved server instance from `Forge` collection.

All operations are performed via `Laravel\Forge\Services\Mysql\MysqlUsers` instance.

All methods return either instance of `Laravel\Forge\Services\Mysql\MysqlUser` or array of `Laravel\Forge\Services\Mysql\MysqlUser` instances.

## Create new MySQL user

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();

$user = $users->create('user', 'password')->on($server);
```

You can call `withAccessTo` method on command to grant user access to databases by its IDs.

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();
$databaseIds = [1234, 12345];

$user = $users->create('user', 'password')
  ->withAccessTo($databaseIds)
  ->on($server);
```

## List MySQL users

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();

$mysqlUsers = $users->list()->from($server);

foreach ($mysqlUsers as $user) {
    echo 'User '.$user->name();
}
```

## Get single MySQL user by ID

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();

$userId = 1234;
$user = $users->get($userId)->from($server);

echo 'User '.$user->name();
```

## Update user

This method may be used to update the databases the MySQL user has access to.

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();
$user = $users->get(1234)->from($server);

$user->update([
  'databases' => [123, 12345],
]);
```

## Delete user

```php
<?php

use Laravel\Forge\Services\Mysql\MysqlUsers;

$users = new MysqlUsers();
$user = $users->get(1234)->from($server);

if ($user->delete()) {
    echo 'User '.$user->name().' was deleted.';
}
```

[Back to Table of Contents](./readme.md)
