<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

abstract class Service implements ServiceContract
{
    /**
     * {@inheritdoc}
     */
    abstract public function name();

    /**
     * {@inheritdoc}
     */
    public function installable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstallable()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rebootable()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function stoppable()
    {
        return true;
    }
}
