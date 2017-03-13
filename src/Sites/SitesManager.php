<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Sites\Commands\GetSiteCommand;
use Laravel\Forge\Sites\Commands\ListSitesCommand;
use Laravel\Forge\Sites\Commands\CreateSiteCommand;

class SitesManager
{
    /**
     * Initialize new create site command.
     *
     * @param string $domain
     *
     * @return \Laravel\Forge\Sites\Commands\CreateSiteCommand
     */
    public function create(string $domain)
    {
        return (new CreateSiteCommand())->identifiedAs($domain);
    }

    /**
     * Initialize new list sites command.
     *
     * @return \Laravel\Forge\Sites\Commands\ListSitesCommand
     */
    public function list()
    {
        return new ListSitesCommand();
    }

    /**
     * Initialize new get site command.
     *
     * @return \Laravel\Forge\Sites\Commands\GetSiteCommand
     */
    public function get(int $siteId)
    {
        return (new GetSiteCommand())->setResourceId($siteId);
    }
}
