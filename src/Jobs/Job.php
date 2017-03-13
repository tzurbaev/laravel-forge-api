<?php

namespace Laravel\Forge\Jobs;

use Laravel\Forge\ApiResource;

class Job extends ApiResource
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
        return $this->getData('command');
    }

    /**
     * Job frequency.
     *
     * @return string|null
     */
    public function frequency()
    {
        return $this->getData('frequency');
    }

    /**
     * Job user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->getData('user');
    }

    /**
     * Cron string for job.
     *
     * @return string|null
     */
    public function cron()
    {
        return $this->getData('cron');
    }
}
