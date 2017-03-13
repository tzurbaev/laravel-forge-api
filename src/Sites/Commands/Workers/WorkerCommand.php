<?php

namespace Laravel\Forge\Sites\Commands\Workers;

use Laravel\Forge\Sites\Worker;
use Laravel\Forge\Sites\Commands\SiteResourceCommand;

abstract class WorkerCommand extends SiteResourceCommand
{
    /**
     * Site resource path.
     *
     * @return string
     */
    public function siteResourcePath()
    {
        return 'workers';
    }

    /**
     * Site resource class.
     *
     * @return string|null
     */
    public function siteResourceClass()
    {
        return Worker::class;
    }
}
