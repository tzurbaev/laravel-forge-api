<?php

namespace Laravel\Forge\Services\Mysql;

use Laravel\Forge\ServerResources\ServerResource;

class MysqlUser extends ServerResource
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
        return $this->data['name'];
    }

    /**
     * Database IDs the user has access to.
     *
     * @return array
     */
    public function databases(): array
    {
        return $this->data['databases'] ?? [];
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
