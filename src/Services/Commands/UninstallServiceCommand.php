<?php

namespace Laravel\Forge\Services\Commands;

class UninstallServiceCommand extends ServiceCommand
{
    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'DELETE';
    }

    /**
     * @{inheritdoc}
     */
    public function command()
    {
        return 'remove';
    }

    /**
     * @{inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->uninstallable();
    }
}
