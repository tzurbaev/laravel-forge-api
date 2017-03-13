<?php

namespace Laravel\Forge\Commands;

use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Contracts\ResourceContract;

trait BooleanResponseTrait
{
    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     *
     * @return bool
     */
    public function handleResponse(ResponseInterface $response, ResourceContract $owner)
    {
        return true;
    }
}
