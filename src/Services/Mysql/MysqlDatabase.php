<?php

namespace Laravel\Forge\Services\Mysql;

use Laravel\Forge\ServerResources\ServerResource;

class MysqlDatabase extends ServerResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'database';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'mysql';
    }

    /**
     * Database name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->getData('name');
    }
}
