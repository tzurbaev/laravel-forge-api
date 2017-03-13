<?php

namespace Laravel\Forge\Commands;

use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Contracts\ResourceContract;

trait RawBodyResponseTrait
{
    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     *
     * @return string
     */
    public function handleResponse(ResponseInterface $response, ResourceContract $owner)
    {
        return (string) $response->getBody();
    }
}
