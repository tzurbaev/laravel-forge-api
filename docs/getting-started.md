# Overview

## PHP SDK for Laravel Forge API

This is unofficial [Laravel Forge API](https://forge.laravel.com/api-documentation) SDK for PHP.

## Supported Features

This SDK supports following Forge API features:

- Servers management - list, create, edit and delete your Forge Servers;
- Services management - install, uninstall, reboot & stop nginx, MySQL, Postgres and other services;
- MySQL databases & users;
- Sites & applications management;
- Deployment management - enable to disable quick deployment, view logs, edit deployment script & deploy-on-demand;
- SSH keys;
- Daemons;
- Firewall rules;
- Scheduled jobs.

## Coming soon

- SSL certificates management;
- Workers;
- Services configuration;
- Recipes.

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

[Back to Table of Contents](./readme.md)
