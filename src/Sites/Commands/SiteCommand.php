<?php

namespace Laravel\Forge\Sites\Commands;

use Laravel\Forge\Sites\Site;
use Laravel\Forge\ServerResources\Commands\ServerResourceCommand;

abstract class SiteCommand extends ServerResourceCommand
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
