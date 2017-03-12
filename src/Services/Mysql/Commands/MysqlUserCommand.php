<?php

namespace Laravel\Forge\Services\Mysql\Commands;

use Laravel\Forge\Services\Mysql\MysqlUser;
use Laravel\Forge\ServerResources\Commands\ServerResourceCommand;

class MysqlUserCommand extends ServerResourceCommand
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
