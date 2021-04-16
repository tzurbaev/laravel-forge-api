# Overview

Once the site was created, you can install Git or WordPress application.

SDK supports Github, Bitbucket, Gitlab and custom git repositories.

# Usage

## Install application from Git repository

```php
<?php

use Laravel\Forge\Applications\GitApplication;

$app = (new GitApplication())->fromGithub('username/repository');
$site->install($app);
```

You can specify which repository provider should be used by calling respective methods.

```php
<?php

use Laravel\Forge\Applications\GitApplication;

// Install application from Github repository.
$app = (new GitApplication())->fromGithub('username/repository');
$app->withComposer(); // If you want to use composer
$site->install($app);

// Install application from Bitbucket repository.
$app = (new GitApplication())->fromBitbucket('username/repository');
$app->withComposer(); // If you want to use composer
$site->install($app);

// Install application from Gitlab repository.
$app = (new GitApplication())->fromGitlab('username/repository');
$app->withComposer(); // If you want to use composer
$site->install($app);

// Install application from custom Git repository.
$app = (new GitApplication())->fromGit('git@example.org:username/repository.git');
$app->withComposer(); // If you want to use composer
$site->install($app);
```

You can indicate which repository branch should be used.

```php
<?php

use Laravel\Forge\Applications\GitApplication;

// Install application from Github repository.
$app = (new GitApplication())->fromGithub('username/repository');
$app->usingBranch('develop');
$app->withComposer(); // If you want to use composer
$site->install($app);
```

## Updating the installed Git application

It is possible to update the installed Git application. Please heed the warnings about changing your application source which can be found on the Forge dashboard.

```php
<?php

use Laravel\Forge\Applications\GitApplication;

// Install application from Github repository.
$app = (new GitApplication())->fromBitBucket('username/repository');
$app->usingBranch('production');
$site->updateApplication($app);
```

## Uninstall Git application

You are not required to provide repository provider or source while uninstalling Git application. Just simply pass instance of `GitApplication` class to `Site::uninstall` method.

```php
<?php

use Laravel\Forge\Applications\GitApplication;

$site->uninstall(new GitApplication());
```

## Install WordPress application

Provide database name and database user name while installing new WordPress application.

```php
<?php

use Laravel\Forge\Applications\WordPressApplication;

$app = (new WordPressApplication())->usingDatabase('database-name', 'user');
$site->install($app);
```

## Uninstall WordPress application

Same as Git applications, you only need to provide empty instance of `WordPressApplication` class to `Site::uninstall` method.

```php
<?php

use Laravel\Forge\Applications\WordPressApplication;

$site->uninstall(new WordPressApplication());
```

[Back to Table of Contents](./readme.md)
