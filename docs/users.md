# Overview

SDK provides Users Manager to retrieve information about currently authenticated Forge user.

# Usage

You can use `Laravel\Forge\Users\UsersService::get()` method to retrieve authenticated Forge user. This method will
return instance of `Laravel\Forge\Users\User` class.

```php
<?php

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Forge;
use Laravel\Forge\Users\UsersService;

$forge = new Forge(
    new ApiProvider('token')
);
$users = new UsersService();
/** @var \Laravel\Forge\Users\User $user */
$user = $users->get()->from($forge);

echo $user->getName();
```

## User Properties

The following user properties are accessible

```php
<?php

$user->id();
$user->getName();
$user->getEmail();
$user->getCardLastFour();
$user->connectedToGitHub();
$user->connectedToGitLab();
$user->connectedToBitbucket();
$user->connectedToBitbucketTwo();
$user->connectedToDigitalOcean();
$user->connectedToLinode();
$user->connectedToVultr();
$user->connectedToAws();
$user->subscribed();
$user->canCreateServers();
```

[Back to Table of Contents](./readme.md)
