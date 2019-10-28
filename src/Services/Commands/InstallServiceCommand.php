<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Commands\ServiceCommand;

class InstallServiceCommand extends ServiceCommand
{
    /**
     * {@inheritdoc}
     */
    public function command()
    {
        return 'install';
    }

    /**
     * {@inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->installable();
    }
}
