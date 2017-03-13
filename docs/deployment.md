# Overview

SDK provides Deployment Manager that allows you to enable or disable quick deployment, view and edit deployment script, reset deployment status, view latest deployment log and deploy application.

# Usage

Documentation assumes that you've already retrieved site instance from `SitesManager` class.

All operations are performed via `Laravel\Forge\Sites\DeploymentManager` instance.

## Enable quick deployment

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

if ($deployment->enable($site)) {
    echo 'Quick deployment enabled on '.$site->domain().' site.';
}
```

## Disable quick deployment

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

if ($deployment->disable($site)) {
    echo 'Quick deployment disabled on '.$site->domain().' site.';
}
```

## Get deployment script

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

$script = $deployment->getScript($site);
```

## Update deployment script

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

$script = "php artisan down\ngit pull origin master\nphp artisan up";

$deployment->updateScript($site, $script);
```

## Reset deployment status

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

$deployment->reset($site);
```

## Get latest deployment log

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

$log = $deployment->log($site);
```

## Deploy now

```php
<?php

use Laravel\Forge\Sites\DeploymentManager;

$deployment = new DeploymentManager();

$deployment->deploy($site);
```

[Back to Table of Contents](./readme.md)
