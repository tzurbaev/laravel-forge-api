<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Commands\Command;
use Laravel\Forge\Contracts\ServiceContract;
use Laravel\Forge\Contracts\ResourceContract;

abstract class ServiceCommand extends Command
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
     * @param \Laravel\Forge\Contracts\ResourceContract $resource
     *
     * @return string
     */
    public function requestUrl(ResourceContract $resource)
    {
        return $resource->apiUrl('/'.$this->getService()->name().'/'.$this->command());
    }
}
