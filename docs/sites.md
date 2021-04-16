# Overview

SDK provides Sites Manager that allows you to create, list and delete sites on your Forge servers.

# Usage

Documentation assumes that you've already retrieved server instance from `Forge` collection.

All operations are performed via `Laravel\Forge\Sites\SitesManager` instance.

All methods return either instance of `Laravel\Forge\Sites\Site` or array of `Laravel\Forge\Sites\Site` instances.

## Create new site

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$site = $sites->create('example.org')->asPhp()->withDirectory('/public')->on($server);
```

Code above will create new site with `example.org` domain and initiate it as General PHP/Laravel Application.

`->withDirectory()` lets Forge know where the public app resides, and will default to `/public` if you leave it out.

You can change site type by calling respective methods.

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

// Create PHP/Laravel site.
$site = $sites->create('example.org')->asPhp()->on($server);
// asLaravel and asGeneralPhp are aliases for asPhp method.
$site = $sites->create('example.org')->asLaravel()->on($server);
$site = $sites->create('example.org')->asGeneralPhp()->on($server);

// Create static HTML site.
$site = $sites->create('example.org')->asStatic()->on($server);
// asHtml is alias for asStatic method
$site = $sites->create('example.org')->asHtml()->on($server);

// Create Symfony site.
$site = $sites->create('example.org')->asSymfony()->on($server);

// Create Symfony (Dev) site.
$site = $sites->create('example.org')->asSymfonyDev()->on($server);

// Create PHP/Laravel site isolated accessible by user_name
$site = $sites->create('example.org')->asPhp()->isolated('user_name')->on($server);
```

## List sites

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$serverSites = $sites->list()->from($server);

foreach ($serverSites as $site) {
    echo 'Site '.$site->domain().' has '.$site->projectType().' type.';
}
```

## Get single site by ID

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$siteId = 1234;
$site = $sites->get($siteId)->from($server);

echo 'Site '.$site->domain().' has '.$site->projectType().' type.';
```

## Update site

This method may be used to update the "web directory" for a given site.

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$site = $sites->get(1234)->from($server);

$site->update(['directory' => '/some/path']);
```

## Load Balancing

Use this method to describe which servers from your network should receive traffic from a load balancer.

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$site = $sites->get(1234)->from($server);

$serverIds = [123, 124];
$site->balance($serverIds);
```

## Delete site

```php
<?php

use Laravel\Forge\Sites\SitesManager;

$sites = new SitesManager();

$site = $sites->get(1234)->from($server);

if ($site->delete()) {
    echo 'Site '.$site->domain().' was deleted.';
}
```

## Site Properties

The following site properties are accessible

```php
<?php

$site->id();
$site->domain();
$site->projectType();
$site->directory();
$site->status();
$site->repository();
$site->repositoryProvider();
$site->repositoryStatus();
$site->repositoryBranch();
$site->deploymentStatus();
$site->wildcards();
$site->quickDeploy();
$site->hipchatRoom();
$site->slackChannel();
$site->app();
$site->appStatus();
$site->createdAt();
```

[Back to Table of Contents](./readme.md)
