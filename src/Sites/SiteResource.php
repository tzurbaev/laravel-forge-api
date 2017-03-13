<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\ServerResources\ServerResource;

abstract class SiteResource extends ServerResource
{
    /**
     * Resource site.
     *
     * @var \Laravel\Forge\Sites\Site
     */
    protected $site;

    /**
     * Get resource site.
     *
     * @return \Laravel\Forge\Sites\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set resource site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return static
     */
    public function setSite(Site $site)
    {
        $this->site = $site;

        return $this;
    }
}
