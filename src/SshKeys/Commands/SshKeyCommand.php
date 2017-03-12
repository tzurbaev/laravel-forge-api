<?php

namespace Laravel\Forge\SshKeys\Commands;

use Laravel\Forge\SshKeys\SshKey;
use Laravel\Forge\ServerResources\Commands\ServerResourceCommand;

abstract class SshKeyCommand extends ServerResourceCommand
{
    /**
     * Server resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'keys';
    }

    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return SshKey::class;
    }
}
