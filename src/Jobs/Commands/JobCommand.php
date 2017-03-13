<?php

namespace Laravel\Forge\Jobs\Commands;

use Laravel\Forge\Jobs\Job;
use Laravel\Forge\Commands\ResourceCommand;

abstract class JobCommand extends ResourceCommand
{
    /**
     * Server resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'jobs';
    }

    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return Job::class;
    }
}
