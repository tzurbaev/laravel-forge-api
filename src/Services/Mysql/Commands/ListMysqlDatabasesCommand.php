<?php

namespace Laravel\Forge\Services\Mysql\Commands;

class ListMysqlDatabasesCommand extends MysqlCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'databases';
    }
}
