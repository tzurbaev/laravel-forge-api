<?php

namespace Laravel\Forge\Workers;

use Laravel\Forge\ApiResource;

class Worker extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'worker';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'workers';
    }

    /**
     * Queue connection.
     *
     * @return string|null
     */
    public function connection()
    {
        return $this->getData('connection');
    }

    /**
     * Worker timeout.
     *
     * @return int
     */
    public function timeout(): int
    {
        return intval($this->getData('timeout'));
    }

    /**
     * Max tries count.
     *
     * @return int
     */
    public function maxTries(): int
    {
        return intval($this->getData('tries'));
    }

    /**
     * Determines if worker is daemon.
     *
     * @return bool
     */
    public function daemon(): bool
    {
        return intval($this->getData('daemon')) === 1;
    }
}
