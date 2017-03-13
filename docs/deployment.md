# Overview

SDK provides Deployment Manager that allows you to enable or disable quick deployment, view and edit deployment script, reset deployment status, view latest deployment log and deploy application.

# Usage

Documentation assumes that you've already retrieved site instance from `SitesManager` class.

All operations are performed via `Laravel\Forge\Deployment\DeploymentManager` instance.

## Enable quick deployment

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

if ($deployment->enable()->on($site)) {
    echo 'Quick deployment enabled on '.$site->domain().' site.';
}
```

## Disable quick deployment

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

if ($deployment->disable()->on($site)) {
    echo 'Quick deployment disabled on '.$site->domain().' site.';
}
```

## Get deployment script

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

$script = $deployment->script()->from($site);
```

## Update deployment script

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

$script = "php artisan down\ngit pull origin master\nphp artisan up";

$deployment->updateScript($script)->on($site);
```

## Reset deployment status

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

$deployment->reset()->on($site);
```

## Get latest deployment log

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

$log = $deployment->log()->from($site);
```

## Deploy now

```php
<?php

use Laravel\Forge\Deployment\DeploymentManager;

$deployment = new DeploymentManager();

$deployment->deploy()->on($site);
```

[Back to Table of Contents](./readme.md)
