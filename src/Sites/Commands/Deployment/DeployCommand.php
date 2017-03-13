<?php

namespace Laravel\Forge\Sites\Commands\Deployment;

use Laravel\Forge\Server;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Sites\Commands\SiteResourceCommand;

class DeployCommand extends SiteResourceCommand
{
    /**
     * Site resource path.
     *
     * @return string
     */
    public function siteResourcePath()
    {
        return 'deployment/deploy';
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'POST';
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
