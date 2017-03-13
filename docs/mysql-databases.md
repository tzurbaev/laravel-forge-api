# Overview

SDK provides extended MySQL Service that allows you to create, list and delete MySQL/MariaDb databases.

# Usage

Documentation assumes that you've already retrieved server instance from `Forge` collection.

All operations are performed via `Laravel\Forge\Services\MysqlService` instance.

All methods return either instance of `Laravel\Forge\Services\Mysql\MysqlDatabase` or array of `Laravel\Forge\Services\Mysql\MysqlDatabase` instances.

## Create new MySQL database

```php
<?php

use Laravel\Forge\Services\MysqlService;

$mysql = new MysqlService();
$database = $mysql->create('database-name')->on($server);
```

You can call `withUser` method to create additional MySQL user that will be granted access to this database.

```php
<?php

use Laravel\Forge\Services\MysqlService;

$mysql = new MysqlService();
$database = $mysql->create('database-name')
  ->withUser('user', 'password')
  ->on($server);
```

## List MySQL databases

```php
<?php

use Laravel\Forge\Services\MysqlService;

$mysql = new MysqlService();
$databases = $mysql->list()->from($server);

foreach ($databases as $database) {
    echo 'Database '.$database->name();
}
```

## Get single database by ID

```php
<?php

use Laravel\Forge\Services\MysqlService;

$mysql = new MysqlService();
$databaseId = 1234;
$database = $mysql->get($databaseId)->from($server);
```

## Delete database

Once you retrieved database you can delete it.

```php
<?php

use Laravel\Forge\Services\MysqlService;

$mysql = new MysqlService();
$databaseId = 1234;
$database = $mysql->get($databaseId)->from($server);

if ($database->delete()) {
    echo 'Database '.$database->name().' was deleted.';
}
```

[Back to Table of Contents](./readme.md)
