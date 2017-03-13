# Overview

SDK provides Daemons Manager that allows you to create, list and delete daemons on your Forge servers.

# Usage

Documentation assumes that you've already retrieved server instance from `ForgeServers` collection.

All operations are performed via `Laravel\Forge\Daemons\DaemonsManager` instance.

All methods return either instance of `Laravel\Forge\Daemons\Daemon` or array of `Laravel\Forge\Daemons\Daemon` instances.

## Create new daemon

```php
<?php

use Laravel\Forge\Daemons\DaemonsManager;

$daemons = new DaemonsManager();
$command = 'php /home/forge/default/artisan daemon:run';
$user = 'forge';

$daemon = $daemons->create($command)->runningAs($user)->on($server);
```

## List daemons

```php
<?php

use Laravel\Forge\Daemons\DaemonsManager;

$daemons = new DaemonsManager();

$serverDaemons = $daemons->list()->from($server);

foreach ($serverDaemons as $daemon) {
    echo 'Daemon command '.$daemon->command().' is running as '.$daemon->user().' user.';
}
```

## Get single daemon by ID

```php
<?php

use Laravel\Forge\Daemons\DaemonsManager;

$daemons = new DaemonsManager();

$daemonId = 1234;
$daemon = $daemons->get($daemonId)->from($server);

echo 'Daemon command '.$daemon->command().' is running as '.$daemon->user().' user.';
```

## Restart daemon

```php
<?php

use Laravel\Forge\Daemons\DaemonsManager;

$daemons = new DaemonsManager();

$daemon = $daemons->get(1234)->from($server);

if ($daemon->restart()) {
    echo 'Daemon '.$daemon->command().' was restarted.';
}
```

## Delete daemon

```php
<?php

use Laravel\Forge\Daemons\DaemonsManager;

$daemons = new DaemonsManager();

$daemon = $daemons->get(1234)->from($server);

if ($daemon->delete()) {
    echo 'Daemon '.$daemon->command().' was deleted.';
}
```

[Back to Table of Contents](./readme.md)
