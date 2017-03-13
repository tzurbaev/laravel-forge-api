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
        // Check if we're working with site resource.
        if (method_exists($this, 'siteResourceClass') && !is_null($this->siteResourceClass())) {
            return $this->siteResourceClass();
        }

        return Site::class;
    }
}
