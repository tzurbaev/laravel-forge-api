<?php

namespace Laravel\Forge\Sites\Commands;

use Laravel\Forge\Server;
use Psr\Http\Message\ResponseInterface;

class GetDeploymentLogCommand extends SiteResourceCommand
{
    /**
     * Site resource path.
     *
     * @return string
     */
    public function siteResourcePath()
    {
        return 'deployment/log';
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod(Server $server)
    {
        return 'GET';
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @return string
     */
    public function handleResponse(ResponseInterface $response, Server $server)
    {
        return (string) $response->getBody();
    }
}
