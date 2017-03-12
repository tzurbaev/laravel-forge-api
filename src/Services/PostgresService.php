<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;

class PostgresService extends AbstractService implements ServiceContract
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'postgres';
    }
}
