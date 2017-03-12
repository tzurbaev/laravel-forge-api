<?php

namespace Laravel\Forge\Services\Mysql\Commands;

class ListMysqlUsersCommand extends MysqlUserCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'users';
    }
}
