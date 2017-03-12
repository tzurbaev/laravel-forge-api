<?php

namespace Laravel\Forge\Daemons\Commands;

class ListDaemonsCommand extends DaemonCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'daemons';
    }
}
