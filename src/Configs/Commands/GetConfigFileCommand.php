<?php

namespace Laravel\Forge\Configs\Commands;

use Laravel\Forge\Commands\RawBodyResponseTrait;
use Laravel\Forge\Commands\NotSupportingResourceClassTrait;

class GetConfigFileCommand extends ConfigFileCommand
{
    use NotSupportingResourceClassTrait, RawBodyResponseTrait;

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'GET';
    }
}
