<?php

namespace Laravel\Forge\Workers\Commands;

use Laravel\Forge\Workers\Worker;
use Laravel\Forge\Commands\ResourceCommand;

abstract class WorkerCommand extends ResourceCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'workers';
    }

    /**
     * Resource class.
     *
     * @return string|null
     */
    public function resourceClass()
    {
        return Worker::class;
    }
}
