<?php

namespace Laravel\Forge\Configs\Commands;

use Laravel\Forge\Commands\BooleanResponseTrait;
use Laravel\Forge\Commands\NotSupportingResourceClassTrait;

class UpdateConfigFileCommand extends ConfigFileCommand
{
    use NotSupportingResourceClassTrait, BooleanResponseTrait;

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'PUT';
    }
}
