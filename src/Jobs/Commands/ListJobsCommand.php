<?php

namespace Laravel\Forge\Jobs\Commands;

class ListJobsCommand extends JobCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'jobs';
    }
}
