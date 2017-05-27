<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Contracts\ApplicationContract;
use Laravel\Forge\ApiResource;

class Site extends ApiResource
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
        return $this->getData('name');
    }

    /**
     * Project type.
     *
     * @return string
     */
    public function projectType()
    {
        return $this->getData('project_type');
    }

    /**
     * Site repository.
     *
     * @return string
     */
    public function repository()
    {
        return $this->getData('repository');
    }

    /**
     * Site repository provider.
     *
     * @return string
     */
    public function repositoryProvider()
    {
        return $this->getData('repository_provider');
    }

    /**
     * Site repository branch.
     *
     * @return string
     */
    public function repositoryBranch()
    {
        return $this->getData('repository_branch');
    }

    /**
     * Site repository status.
     *
     * @return string
     */
    public function repositoryStatus()
    {
        return $this->getData('repository_status');
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
            'json' => $application->payload(),
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

    /**
     * Connect load balancer.
     *
     * @return bool
     */
    public function balance(array $serverIds)
    {
        $this->getHttpClient()->request('PUT', $this->apiUrl('/balancing'), [
            'json' => ['servers' => $serverIds]
        ]);

        return true;
    }
}
