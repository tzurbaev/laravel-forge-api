<?php

namespace Laravel\Forge\Deployment\Commands;

use Laravel\Forge\Commands\ResourceCommand;
use Laravel\Forge\Commands\BooleanResponseTrait;
use Laravel\Forge\Commands\NotSupportingResourceClassTrait;

class DisableDeploymentCommand extends ResourceCommand
{
    use NotSupportingResourceClassTrait, BooleanResponseTrait;

    /**
     * Site resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'deployment';
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'DELETE';
    }
}
