<?php

namespace Laravel\Forge\Services\Commands;

class RebootServiceCommand extends ServiceCommand
{
    /**
     * @{inheritdoc}
     */
    public function command()
    {
        return 'reboot';
    }

    /**
     * @{inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->rebootable();
    }
}
