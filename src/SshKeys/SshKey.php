<?php

namespace Laravel\Forge\SshKeys;

use Laravel\Forge\Resource;

class SshKey extends Resource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'key';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'keys';
    }
}
