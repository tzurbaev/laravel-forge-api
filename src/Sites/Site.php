<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Contracts\ApplicationContract;
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

    /**
     * Install new application on site.
     *
     * @param \Laravel\Forge\Contracts\ApplicationContract $application
     *
     * @return bool
     */
    public function install(ApplicationContract $application)
    {
        $this->getHttpClient()->request('POST', $this->apiUrl($application->type()), [
            'form_params' => $application->payload(),
        ]);

        return true;
    }

    /**
     * Uninstall application from site.
     *
     * @param \Laravel\Forge\Contracts\ApplicationContract $application
     *
     * @return bool
     */
    public function uninstall(ApplicationContract $application)
    {
        $this->getHttpClient()->request('DELETE', $this->apiUrl($application->type()));

        return true;
    }
}
