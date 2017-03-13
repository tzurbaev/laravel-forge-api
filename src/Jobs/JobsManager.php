<?php

namespace Laravel\Forge\Jobs;

use Laravel\Forge\Jobs\Commands\GetJobCommand;
use Laravel\Forge\Jobs\Commands\ListJobsCommand;
use Laravel\Forge\Jobs\Commands\CreateJobCommand;

class JobsManager
{
    /**
     * Initialize new create job command.
     *
     * @param string $command
     *
     * @return \Laravel\Forge\Jobs\Commands\CreateJobCommand
     */
    public function schedule(string $command)
    {
        return (new CreateJobCommand())->schedule($command);
    }

    /**
     * Initialize new list jobs command.
     *
     * @return \Laravel\Forge\Jobs\Commands\ListJobsCommand
     */
    public function list()
    {
        return new ListJobsCommand();
    }

    /**
     * Initialize new get job command.
     *
     * @param int $jobId
     *
     * @return \Laravel\Forge\Jobs\Commands\GetJobCommand
     */
    public function get(int $jobId)
    {
        return (new GetJobCommand())->setResourceId($jobId);
    }
}
