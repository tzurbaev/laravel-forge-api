<?php

namespace Laravel\Forge\Services\Mysql\Commands;

use Laravel\Forge\Commands\ResourceCommand;
use Laravel\Forge\Services\Mysql\MysqlDatabase;

abstract class MysqlDatabaseCommand extends ResourceCommand
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
