<?php

namespace Laravel\Forge\Services;

class PostgresService extends Service
{
    /**
     * @{inheritdoc}
     */
    public function name()
    {
        return 'postgres';
    }
}
