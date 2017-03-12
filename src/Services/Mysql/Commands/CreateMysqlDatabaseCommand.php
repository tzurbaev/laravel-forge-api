<?php

namespace Laravel\Forge\Services\Mysql\Commands;

class CreateMysqlDatabaseCommand extends MysqlCommand
{
    /**
     * Set database name.
     *
     * @param string $name
     *
     * @return static
     */
    public function identifiedAs(string $name)
    {
        return $this->attachPayload('name', $name);
    }

    /**
     * Also create database user and password.
     *
     * @param string $user
     * @param string $password
     *
     * @return static
     */
    public function withUser(string $user, string $password)
    {
        return $this->attachPayload('user', $user)->attachPayload('password', $password);
    }
}
