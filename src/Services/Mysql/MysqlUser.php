<?php

namespace Laravel\Forge\Services\Mysql;

use Laravel\Forge\ApiResource;

class MysqlUser extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'user';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'mysql-users';
    }

    /**
     * User name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->getData('name');
    }

    /**
     * Database IDs the user has access to.
     *
     * @return array
     */
    public function databases(): array
    {
        return $this->getData('databases', []);
    }

    /**
     * Determines if user has access to given database.
     *
     * @param int|\Laravel\Forge\Services\Mysql\MysqlDatabase $database
     *
     * @return bool
     */
    public function hasAccessTo($database): bool
    {
        $databaseId = ($database instanceof MysqlDatabase ? $database->id() : intval($database));

        return in_array($databaseId, $this->databases());
    }
}
