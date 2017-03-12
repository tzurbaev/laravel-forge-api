<?php

namespace Laravel\Forge\SshKeys;

use Laravel\Forge\ServerResources\ServerResource;

class SshKey extends ServerResource
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

    /**
     * Key name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->data['name'];
    }
}
