<?php

namespace Laravel\Forge\Daemons;

use Laravel\Forge\ServerResources\ServerResource;

class Daemon extends ServerResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'daemon';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'daemons';
    }

    /**
     * Daemon command.
     *
     * @return string|null
     */
    public function command()
    {
        return $this->data['command'];
    }

    /**
     * Daemon user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->data['user'];
    }

    /**
     * Restart daemon.
     *
     * @return bool
     */
    public function restart()
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('/restart'));

        return true;
    }
}
