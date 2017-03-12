<?php

namespace Laravel\Forge\Jobs;

use Laravel\Forge\ServerResources\ServerResource;

class Job extends ServerResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'job';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'jobs';
    }

    /**
     * Job command.
     *
     * @return string|null
     */
    public function command()
    {
        return $this->data['command'];
    }

    /**
     * Job frequency.
     *
     * @return string|null
     */
    public function frequency()
    {
        return $this->data['frequency'];
    }

    /**
     * Job user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->data['user'];
    }

    /**
     * Cron string for job.
     *
     * @return string|null
     */
    public function cron()
    {
        return $this->data['cron'];
    }
}
