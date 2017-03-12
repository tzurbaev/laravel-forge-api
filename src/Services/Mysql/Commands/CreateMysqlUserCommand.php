<?php

namespace Laravel\Forge\Services\Mysql\Commands;

use Laravel\Forge\Services\Mysql\MysqlDatabase;

class CreateMysqlUserCommand extends MysqlUserCommand
{
    /**
     * Set username and password.
     *
     * @param string $name
     * @param string $password
     *
     * @return static
     */
    public function identifiedAs(string $name, string $password)
    {
        return $this->attachPayload('name', $name)
            ->attachPayload('password', $password);
    }

    /**
     * Databases the user will have access to.
     *
     * @param array|int|\Laravel\Forge\Services\Mysql\MysqlDatabase $databases
     *
     * @return static
     */
    public function withAccessTo($databases)
    {
        return $this->attachPayload('databases', $this->extractDatabaseIds($databases));
    }

    /**
     * Extracts database IDs from single MysqlDatabase instance,
     * single integer ID, array of MysqlDatabase instances or from
     * array of integer IDs.
     *
     * @return array
     */
    protected function extractDatabaseIds($databases): array
    {
        if ($databases instanceof MysqlDatabase) {
            return [$databases->id()];
        } elseif (is_integer($databases)) {
            return [intval($databases)];
        }

        $databaseIds = [];

        foreach ($databases as $database) {
            if ($database instanceof MysqlDatabase) {
                $databaseIds[] = $database->id();
            } else {
                $databaseIds[] = intval($database);
            }
        }

        return $databaseIds;
    }
}
