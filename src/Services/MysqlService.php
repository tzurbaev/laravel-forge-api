<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Services\Mysql\Commands\GetMysqlDatabaseCommand;
use Laravel\Forge\Services\Mysql\Commands\ListMysqlDatabasesCommand;
use Laravel\Forge\Services\Mysql\Commands\CreateMysqlDatabaseCommand;

class MysqlService extends Service
{
    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return 'mysql';
    }

    /**
     * Initialize new create MySQL database command.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\CreateMysqlDatabaseCommand
     */
    public function create(string $name)
    {
        return (new CreateMysqlDatabaseCommand())->identifiedAs($name);
    }

    /**
     * Initialize new list MySQL databases command.
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\ListMysqlDatabasesCommand
     */
    public function list()
    {
        return new ListMysqlDatabasesCommand();
    }

    /**
     * Initialize new get MySQL database command.
     *
     * @param int $databaseId
     *
     * @return \Laravel\Forge\Services\Mysql\Commands\GetMysqlDatabaseCommand
     */
    public function get(int $databaseId)
    {
        return (new GetMysqlDatabaseCommand())->setResourceId($databaseId);
    }
}
