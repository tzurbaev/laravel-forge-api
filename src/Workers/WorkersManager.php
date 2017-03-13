<?php

namespace Laravel\Forge\Workers;

use Laravel\Forge\Workers\Commands\GetWorkerCommand;
use Laravel\Forge\Workers\Commands\ListWorkersCommand;
use Laravel\Forge\Workers\Commands\CreateWorkerCommand;

class WorkersManager
{
    /**
     * Initialize new create worker command.
     *
     * @param string $connection
     *
     * @return \Laravel\Forge\Workers\Commands\CreateWorkerCommand
     */
    public function start(string $connection)
    {
        return (new CreateWorkerCommand())->usingConnection($connection);
    }

    /**
     * Alias for "start" method.
     *
     * @param string $connection
     *
     * @return \Laravel\Forge\Workers\Commands\CreateWorkerCommand
     */
    public function create(string $connection)
    {
        return $this->start($connection);
    }

    /**
     * Initialize new get worker command.
     *
     * @param int $workerId
     *
     * @return \Laravel\Forge\Workers\Commands\GetWorkerCommand
     */
    public function get(int $workerId)
    {
        return (new GetWorkerCommand())->setResourceId($workerId);
    }

    /**
     * Initialize new list workers command.
     *
     * @return \Laravel\Forge\Workers\Commands\ListWorkersCommand
     */
    public function list()
    {
        return new ListWorkersCommand();
    }
}
