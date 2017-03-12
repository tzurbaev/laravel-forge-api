<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class NginxService extends AbstractService implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'nginx';
    }
}
