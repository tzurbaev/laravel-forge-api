<?php

namespace Laravel\Forge\Services;

class NginxService extends Service
{
    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'nginx';
    }
}
