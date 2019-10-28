<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Commands\ServiceCommand;

class StopServiceCommand extends ServiceCommand
{
    /**
     * {@inheritdoc}
     */
    public function command()
    {
        return 'stop';
    }

    /**
     * {@inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->stoppable();
    }
}
