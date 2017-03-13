<?php

namespace Laravel\Forge\Sites\Commands;

use Laravel\Forge\Sites\Site;
use Laravel\Forge\Commands\ResourceCommand;

abstract class SiteCommand extends ResourceCommand
{
    /**
     * Server resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'sites';
    }

    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return Site::class;
    }
}
