<?php

namespace Laravel\Forge\Applications;

abstract class Application
{
    /**
     * Application payload.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * Application request payload.
     *
     * @return array
     */
    public function payload()
    {
        return $this->payload;
    }
}
