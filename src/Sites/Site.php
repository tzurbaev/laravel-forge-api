<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\ServerResources\ServerResource;

class Site extends ServerResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'site';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'sites';
    }

    /**
     * Site domain.
     *
     * @return string
     */
    public function domain()
    {
        return $this->data['name'];
    }

    /**
     * Project type.
     *
     * @return string
     */
    public function projectType()
    {
        return $this->data['project_type'];
    }
}
