<?php

namespace Laravel\Forge\Services\Commands;

class StopServiceCommand extends AbstractServiceCommand
{
    /**
     * @{inheritdoc}
     */
    public function command()
    {
        return 'stop';
    }

    /**
     * @{inheritdoc}
     */
    public function runnable()
    {
        return $this->service->stoppable();
    }
}
