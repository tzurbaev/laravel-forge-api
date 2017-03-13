<?php

namespace Laravel\Forge\Deployment\Commands;

use Laravel\Forge\Commands\ResourceCommand;
use Laravel\Forge\Commands\BooleanResponseTrait;
use Laravel\Forge\Commands\NotSupportingResourceClassTrait;

class EnableDeploymentCommand extends ResourceCommand
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
}
