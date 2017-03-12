<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class NginxService extends Service implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'nginx';
    }
}
