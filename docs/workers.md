# Overview

SDK provides Workers Manager that allows you to create, list and delete workers for your Forge sites.

# Usage

Documentation assumes that you've already retrieved site instance from `SitesManager` class.

All operations are performed via `Laravel\Forge\Sites\WorkersManager` instance.

All methods return either instance of `Laravel\Forge\Sites\Worker` or array of `Laravel\Forge\Sites\Worker` instances.

## Create new worker

You can specify worker connection, queue name, timeout, sleep seconds, max tries count and indicate that worker should be created as daemon.

```php
<?php

use Laravel\Forge\Sites\WorkersManager;

$workers = new WorkersManager();

$worker = $workers()->start('sqs')
    ->withTimeout(90)
    ->sleepFor(3)
    ->maxTries(3)
    ->asDaemon()
    ->onQueue('my-queue-name')
    ->for($site);
```

## List workers

```php
<?php

use Laravel\Forge\Sites\WorkersManager;

$workers = new WorkersManager();

$siteWorkers = $workers->list()->for($site);

foreach ($siteWorkers as $worker) {
    echo 'Worker is running on '.$worker->connection().' connection, has timeout of '.$worker->timeout().' seconds and '.$worker->maxTries().'max tries.';
}
```

## Get single worker by ID

```php
<?php

use Laravel\Forge\Sites\WorkersManager;

$workers = new WorkersManager();

$workerId = 1234;
$worker = $workers->get($workerId)->for($site);

echo 'Worker is running on '.$worker->connection().' connection, has timeout of '.$worker->timeout().' seconds and '.$worker->maxTries().'max tries.';
```

## Delete worker

```php
<?php

use Laravel\Forge\Sites\WorkersManager;

$workers = new WorkersManager();

$worker = $workers->get($workerId)->for($site);

if ($worker->delete()) {
    echo 'Worker was deleted.';
}
```

[Back to Table of Contents](./readme.md)
