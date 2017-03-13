<?php

namespace Laravel\Forge\Services\Mysql\Commands;

use Laravel\Forge\Commands\ResourceCommand;
use Laravel\Forge\Services\Mysql\MysqlUser;

abstract class MysqlUserCommand extends ResourceCommand
{
    /**
     * Server resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'mysql-users';
    }

    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return MysqlUser::class;
    }
}
