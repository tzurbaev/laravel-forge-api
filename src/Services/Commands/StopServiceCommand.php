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
}
