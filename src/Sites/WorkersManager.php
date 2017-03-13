<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Sites\Commands\Workers\GetWorkerCommand;
use Laravel\Forge\Sites\Commands\Workers\ListWorkersCommand;
use Laravel\Forge\Sites\Commands\Workers\CreateWorkerCommand;

class WorkersManager
{
    /**
     * Initialize new create worker command.
     *
     * @param string $connection
     *
     * @return \Laravel\Forge\Sites\Commands\Workers\CreateWorkerCommand
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
     * @return \Laravel\Forge\Sites\Commands\Workers\CreateWorkerCommand
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
     * @return \Laravel\Forge\Sites\Commands\Workers\GetWorkerCommand
     */
    public function get(int $workerId)
    {
        return (new GetWorkerCommand())->setSiteResourceId($workerId);
    }

    /**
     * Initialize new list workers command.
     *
     * @return \Laravel\Forge\Sites\Commands\Workers\ListWorkersCommand
     */
    public function list()
    {
        return new ListWorkersCommand();
    }
}
