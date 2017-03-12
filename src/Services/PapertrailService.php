<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class PapertrailService extends Service implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'papertrail';
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
