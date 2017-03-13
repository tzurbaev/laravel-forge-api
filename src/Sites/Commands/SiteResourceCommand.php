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
     * Resource ID.
     *
     * @var int
     */
    protected $siteResourceId = 0;

    /**
     * Site resource path.
     *
     * @return string
     */
    abstract public function siteResourcePath();

    /**
     * Site resource class.
     *
     * @return string|null
     */
    public function siteResourceClass()
    {
        //
    }

    /**
     * Set associated site and execute command on site server.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return static
     */
    public function for(Site $site)
    {
        $this->site = $site;
        $this->setItemId($site->id());

        return $this->on($site->getServer());
    }

    /**
     * Set site resource ID.
     *
     * @param int $resourceId
     *
     * @return static
     */
    public function setSiteResourceId(int $resourceId)
    {
        $this->siteResourceId = $resourceId;

        return $this;
    }

    /**
     * Get site resource ID.
     *
     * @return int
     */
    public function getSiteResourceId(): int
    {
        return $this->siteResourceId;
    }

    /**
     * Get associated site.
     *
     * @return \Laravel\Forge\Sites\Site
     */
    public function getSite(): Site
    {
        return $this->site;
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
     * Processes new response item.
     *
     * @param mixed $item
     *
     * @return mixed
     */
    public function processResponseItem($item)
    {
        return $item->setSite($this->getSite());
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @return bool|string
     */
    public function handleResponse(ResponseInterface $response, Server $server)
    {
        $siteResourceClass = $this->siteResourceClass();

        if (is_null($siteResourceClass)) {
            return true;
        }

        return parent::handleResponse($response, $server);
    }
}
