<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Commands\ServiceCommand;

class RebootServiceCommand extends ServiceCommand
{
    /**
     * {@inheritdoc}
     */
    public function command()
    {
        return 'reboot';
    }

    /**
     * {@inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->rebootable();
    }
}
