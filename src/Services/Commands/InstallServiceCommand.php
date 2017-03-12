<?php

namespace Laravel\Forge\Services\Commands;

class InstallServiceCommand extends AbstractServiceCommand
{
    /**
     * @{inheritdoc}
     */
    public function command()
    {
        return 'install';
    }

    /**
     * @{inheritdoc}
     */
    public function runnable()
    {
        return $this->service->installable();
    }
}
