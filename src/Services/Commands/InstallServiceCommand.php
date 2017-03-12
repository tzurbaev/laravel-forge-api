<?php

namespace Laravel\Forge\Services\Commands;

class InstallServiceCommand extends ServiceCommand
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
        return $this->getService()->installable();
    }
}
