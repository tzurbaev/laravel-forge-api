<?php

namespace Laravel\Forge\Daemons;

use Laravel\Forge\ApiResource;

class Daemon extends ApiResource
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
        return $this->getData('command');
    }

    /**
     * Daemon user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->getData('user');
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
