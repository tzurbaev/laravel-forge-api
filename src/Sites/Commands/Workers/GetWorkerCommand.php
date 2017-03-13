<?php

namespace Laravel\Forge\Sites\Commands\Workers;

class GetWorkerCommand extends WorkerCommand
{
    /**
     * Site resource path.
     *
     * @return string
     */
    public function siteResourcePath()
    {
        return 'workers/'.$this->getSiteResourceId();
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'GET';
    }
}
