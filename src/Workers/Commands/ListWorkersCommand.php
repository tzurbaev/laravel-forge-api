<?php

namespace Laravel\Forge\Workers\Commands;

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
}
