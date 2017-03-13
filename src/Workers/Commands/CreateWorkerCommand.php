<?php

namespace Laravel\Forge\Workers\Commands;

class CreateWorkerCommand extends WorkerCommand
{
    /**
     * Set connection name.
     *
     * @param string $connection
     *
     * @return static
     */
    public function usingConnection(string $connection)
    {
        return $this->attachPayload('connection', $connection);
    }

    /**
     * Set queue name.
     *
     * @param string $queue
     *
     * @return static
     */
    public function onQueue(string $queue)
    {
        return $this->attachPayload('queue', $queue);
    }

    /**
     * Set timeout.
     *
     * @param int $seconds
     *
     * @return static
     */
    public function withTimeout(int $seconds)
    {
        return $this->attachPayload('timeout', $seconds);
    }

    /**
     * Set sleep seconds.
     *
     * @param int $seconds
     *
     * @return static
     */
    public function sleepFor(int $seconds)
    {
        return $this->attachPayload('sleep', $seconds);
    }

    /**
     * Set max tries count.
     *
     * @param int $tries
     *
     * @return static
     */
    public function maxTries(int $tries)
    {
        return $this->attachPayload('tries', $tries);
    }

    /**
     * Indicates that worker should start as daemon.
     *
     * @return static
     */
    public function asDaemon()
    {
        return $this->attachPayload('daemon', true);
    }
}
