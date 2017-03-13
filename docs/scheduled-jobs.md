# Overview

SDK provides Schedule Manager that allows you to create, list and delete scheduled jobs on your Forge servers.

# Usage

Documentation assumes that you've already retrieved server instance from `Forge` collection.

All operations are performed via `Laravel\Forge\Jobs\JobsManager` instance.

All methods return either instance of `Laravel\Forge\Jobs\Job` or array of `Laravel\Forge\Jobs\Job` instances.

## Create new job

```php
<?php

use Laravel\Forge\Jobs\JobsManager;

$jobs = new JobsManager();
$command = 'php /home/forge/default/artisan schedule:run';
$user = 'forge';

$job = $jobs->schedule($command)
    ->runningAs($user)

    ->everyMinute() // command will run every minute
    ->hourly() // comamnd will run every hour
    ->nightly() // command will run every midnight
    ->weekly() // command will run every week
    ->monthly() // command will run every month

    ->atTime('12:33') // command will run at given time
    ->atDay(1) // command will run on 1st day of every month
    ->atMonth(3) // command will run on every March
    ->atWeekday(6) // command will run on every Saturday
    ->on($server);
```

## List jobs

```php
<?php

use Laravel\Forge\Jobs\JobsManager;

$jobs = new JobsManager();

$serverJobs = $jobs->list()->from($server);

foreach ($serverJobs as $job) {
    echo 'Job '.$job->command().' is running as '.$job->user().' user with '.$job->frequency().' frequency';
}
```

## Get single job by ID

```php
<?php

use Laravel\Forge\Jobs\JobsManager;

$jobs = new JobsManager();

$jobId = 1234;
$job = $jobs->get($jobId)->from($server);

echo 'Job '.$job->command().' is running as '.$job->user().' user with '.$job->frequency().' frequency';
```

## Delete job

```php
<?php

use Laravel\Forge\Jobs\JobsManager;

$jobs = new JobsManager();

$job = $jobs->get(1234)->from($server);

if ($job->delete()) {
    echo 'Job '.$job->command().' was deleted.';
}
```

[Back to Table of Contents](./readme.md)
