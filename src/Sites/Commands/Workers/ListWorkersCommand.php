<?php

namespace Laravel\Forge\Sites\Commands\Workers;

class ListWorkersCommand extends WorkerCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'workers';
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
