<?php

namespace Laravel\Forge\Sites\Commands;

use Laravel\Forge\Server;
use Psr\Http\Message\ResponseInterface;

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
    public function requestMethod(Server $server)
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
