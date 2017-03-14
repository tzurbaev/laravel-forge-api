# Overview

## PHP SDK for Laravel Forge API

This is unofficial [Laravel Forge API](https://forge.laravel.com/api-documentation) SDK for PHP.

## Supported Features

This SDK supports following Forge API features:

- Servers management - list, create, edit and delete your Forge Servers;
- Services management - install, uninstall, reboot & stop nginx, MySQL, Postgres and other services;
- MySQL databases & users;
- Sites & applications management;
- Site configuration (`nginx.conf` & `.env` files);
- Deployment management - enable to disable quick deployment, view logs, edit deployment script & deploy-on-demand;
- SSH keys;
- Daemons;
- Firewall rules;
- Scheduled jobs;
- Workers;
- Recipes.

## Coming soon

- SSL certificates management.

# Requirements

- PHP 7.0+;
- [Composer](https://getcomposer.org);
- Laravel Forge API token ([create new token](https://forge.laravel.com/user/profile));

# Dependencies

This package depends on `guzzlehttp/guzzle ~6.0`. If you're using older version of Guzzle, make sure to update your code to avoid version conflicts.

# Installation

The recommended way to install the SDK is with [Composer](https://getcomposer.org).

```sh
composer require tzurbaev/laravel-forge-api ^1.0
```

Alternatively, you can specify the SDK as a dependency in your project's existing composer.json file:

```json
{
  "require": {
    "tzurbaev/laravel-forge-api": "^1.0"
  }
}
```

# Laravel Integration

1. Add `Laravel\Forge\Laravel\ForgeServiceProvider` to your providers list (`config/app.php`);
2. Run `php artisan vendor:publish --provider="Laravel\Forge\Laravel\ForgeServiceProvider"` command;
3. Edit `config/forge.php` configuration file or add token to `FORGE_TOKEN` environment variable (`.env` file);

Now you should be able to run `php artisan forge:credentials` command to list your Forge credentials and `php artisan forge:servers` to list, create and delete servers.

Since package's Service Provider bootstraps Forge class with your credentials, you can simply inject `Laravel\Forge\Forge` class via Laravel's Dependency Injection:

```php
<?php

namespace App\Http\Controllers;

use Laravel\Forge\Forge;
use Illuminate\Http\Request;

class ForgeController extends Controller
{
    public function index(Request $request, Forge $forge)
    {
        // $forge is ready to use.
    }

    public function update(Request $request)
    {
        $forge = app(Forge::class);

        // $forge is ready to use.
    }
}
```

[Back to Table of Contents](./readme.md)
