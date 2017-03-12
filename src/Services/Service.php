<?php

namespace Laravel\Forge\Services;

abstract class Service
{
    /**
     * @{inheritdoc}
     */
    abstract public function name();

    /**
     * @{inheritdoc}
     */
    public function installable()
    {
        return false;
    }

    /**
     * @{inheritdoc}
     */
    public function uninstallable()
    {
        return false;
    }

    /**
     * @{inheritdoc}
     */
    public function rebootable()
    {
        return true;
    }

    /**
     * @{inheritdoc}
     */
    public function stoppable()
    {
        return true;
    }
}
