<?php

namespace Laravel\Forge\Services\Mysql;

use Laravel\Forge\Services\Mysql\Commands\GetMysqlUserCommand;
use Laravel\Forge\Services\Mysql\Commands\ListMysqlUsersCommand;
use Laravel\Forge\Services\Mysql\Commands\CreateMysqlUserCommand;

class MysqlUsers
{
    /**
     * Initialize new create MySQL user command.
     *
     * @param string $name
     * @param string $password
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\CreateMysqlUserCommand
     */
    public function create(string $name, string $password)
    {
        return (new CreateMysqlUserCommand())->identifiedAs($name, $password);
    }

    /**
     * Initialize new list MySQL users command.
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\ListMysqlUsersCommand
     */
    public function list()
    {
        return new ListMysqlUsersCommand();
    }

    /**
     * Initialize new get MySQL user command.
     *
     * @param int $userId
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\GetMysqlUserCommand
     */
    public function get(int $userId)
    {
        return (new GetMysqlUserCommand())->setItemId($userId);
    }
}
