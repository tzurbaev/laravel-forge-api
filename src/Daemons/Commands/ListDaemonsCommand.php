<?php

namespace Laravel\Forge\Daemons\Commands;

use Laravel\Forge\Server;
use InvalidArgumentException;
use Laravel\Forge\Daemons\Daemon;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Commands\ServerCommand;

class ListDaemonsCommand extends ServerCommand
{
    /**
     * Command name.
     *
     * @return string
     */
    public function command()
    {
        return 'daemons';
    }

    /**
     * HTTP request method.
     *
     * @param \Laravel\Forge\Server
     *
     * @return string
     */
    public function requestMethod(Server $server)
    {
        return 'GET';
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
        return $server->apiUrl('/daemons');
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @return \Laravel\Forge\Daemons\Daemon
     */
    public function handleResponse(ResponseInterface $response, Server $server)
    {
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['daemons'])) {
            throw new InvalidArgumentException('Given response is not a daemons response.');
        }

        $daemons = [];
        $api = $server->getApi();
        $serverId = $server->id();

        foreach ($json['daemons'] as $daemon) {
            $daemons[] = new Daemon($api, $daemon, $serverId);
        }

        return $daemons;
    }
}
