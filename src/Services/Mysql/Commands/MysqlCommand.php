<?php

namespace Laravel\Forge\Services\Mysql\Commands;

use Laravel\Forge\Services\Mysql\MysqlDatabase;
use Laravel\Forge\ServerResources\Commands\ServerResourceCommand;

class MysqlCommand extends ServerResourceCommand
{
    /**
     * Server resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'mysql';
    }

    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return MysqlDatabase::class;
    }
}
