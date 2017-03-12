<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class BlackfireService extends AbstractService implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'blackfire';
    }

    /**
     * @{inheritdoc}
     */
    public function installable()
    {
        return true;
    }

    /**
     * @{inheritdoc}
     */
    public function uninstallable()
    {
        return true;
    }

    /**
     * @{inheritdoc}
     */
    public function rebootable()
    {
        return false;
    }

    /**
     * @{inheritdoc}
     */
    public function stoppable()
    {
        return false;
    }
}
