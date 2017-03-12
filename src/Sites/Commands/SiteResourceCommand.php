<?php

namespace Laravel\Forge\Sites\Commands;

use Laravel\Forge\Server;
use Laravel\Forge\Sites\Site;
use Psr\Http\Message\ResponseInterface;

abstract class SiteResourceCommand extends SiteCommand
{
    /**
     * Associated site.
     *
     * @var \Laravel\Forge\Sites\Site
     */
    protected $site;

    /**
     * Site resource path.
     *
     * @return string
     */
    abstract public function siteResourcePath();

    /**
     * Set associated site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return static
     */
    public function for(Site $site)
    {
        $this->site = $site;
        $this->setItemId($site->id());

        return $this;
    }

    /**
     * HTTP request URL.
     *
     * @param \Laravel\Forge\Server
     *
     * @return string
     */
    public function requestUrl(Server $server)
    {
        $requestUrl = parent::requestUrl($server);

        return $requestUrl.'/'.$this->siteResourcePath();
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @return bool
     */
    public function handleResponse(ResponseInterface $response, Server $server)
    {
        return true;
    }
}
