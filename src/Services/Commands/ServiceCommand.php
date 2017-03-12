<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Server;
use Laravel\Forge\Commands\ServerCommand;
use Laravel\Forge\Contracts\ServiceContract;

abstract class ServiceCommand extends ServerCommand
{
    /**
     * Associated service.
     *
     * @var \Laravel\Forge\Contracts\ServiceContract
     */
    protected $service;

    /**
     * Create new command instance.
     *
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     */
    public function __construct(ServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Associated service.
     *
     * @return \Laravel\Forge\Contracts\ServiceContract
     */
    public function getService(): ServiceContract
    {
        return $this->service;
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
        return $server->apiUrl('/'.$this->getService()->name().'/'.$this->command());
    }
}
