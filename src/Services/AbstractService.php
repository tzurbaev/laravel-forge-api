<?php

namespace Laravel\Forge\Services;

abstract class AbstractService
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
}
