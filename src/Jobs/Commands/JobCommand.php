<?php

namespace Laravel\Forge\Jobs\Commands;

use Laravel\Forge\Jobs\Job;
use Laravel\Forge\ServerResources\Commands\ServerResourceCommand;

abstract class JobCommand extends ServerResourceCommand
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
