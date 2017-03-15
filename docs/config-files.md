# Overview

SDK provides Configs Manager that allows you to view and edit NGINX and ENV configuration files for your sites.

# Usage

Documentation assumes that you've already retrieved site instance from `SitesManager` class.

All operations are performed via `Laravel\Forge\Configs\ConfigsManager` instance.

## Get Nginx configuration

```php
<?php

use Laravel\Forge\Configs\ConfigsManager;

$configs = new ConfigsManager();
$nginxConfiguration = $configs->get('nginx')->from($site);
```

The value of `$nginxConfiguration` is actual content of your nginx configuration file.

## Update Nginx configuration

```php
<?php

use Laravel\Forge\Configs\ConfigsManager;

$configs = new ConfigsManager();

// Load new configuration file from appropirate source.
$configuration = file_get_contents('nginx.conf');

if ($configs->update('nginx', $configuration)->on($site)) {
    echo 'Nginx configuration on '.$site->domain().' site was updated.';
}
```

## Get ENV configuration

ENV confguration stored in `.env` file of your application.

```php
<?php

use Laravel\Forge\Configs\ConfigsManager;

$configs = new ConfigsManager();

$env = $configs->get('env')->from($site);
```

The value of `$env` is actual content of your `.env` configuration file.

## Update ENV configuration

```php
<?php

use Laravel\Forge\Configs\ConfigsManager;

$configs = new ConfigsManager();

// Load new configuration file from appropirate source.
$configuration = file_get_contents('.env');

if ($configs->update('env', $configuration)->on($site)) {
    echo 'ENV configuration on '.$site->domain().' site was updated.';
}
```

[Back to Table of Contents](./readme.md)
