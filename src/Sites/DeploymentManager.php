<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Sites\Commands\Deployment\DeployCommand;
use Laravel\Forge\Sites\Commands\Deployment\EnableDeploymentCommand;
use Laravel\Forge\Sites\Commands\Deployment\GetDeploymentLogCommand;
use Laravel\Forge\Sites\Commands\Deployment\DisableDeploymentCommand;
use Laravel\Forge\Sites\Commands\Deployment\GetDeploymentScriptCommand;
use Laravel\Forge\Sites\Commands\Deployment\ResetDeploymentStatusCommand;
use Laravel\Forge\Sites\Commands\Deployment\UpdateDeploymentScriptCommand;

class DeploymentManager
{
    /**
     * Enable quick deployment on given site.
     *
     * @param \Laravel\Forge\Sites\Site
     *
     * @return bool
     */
    public function enable(Site $site)
    {
        return (new EnableDeploymentCommand())->for($site);
    }

    /**
     * Disable quick deployment on given site.
     *
     * @param \Laravel\Forge\Sites\Site
     *
     * @return bool
     */
    public function disable(Site $site)
    {
        return (new DisableDeploymentCommand())->for($site);
    }

    /**
     * Get deployment script from given site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return string
     */
    public function getScript(Site $site)
    {
        return (new GetDeploymentScriptCommand())->for($site);
    }

    /**
     * Update deployment script for given site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     * @param string                    $script
     *
     * @return bool
     */
    public function updateScript(Site $site, string $script)
    {
        return (new UpdateDeploymentScriptCommand())
            ->withPayload(['content' => $script])
            ->for($site);
    }

    /**
     * Perform deployment of given site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return bool
     */
    public function deploy(Site $site)
    {
        return (new DeployCommand())->for($site);
    }

    /**
     * Reset deployment status for given site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return bool
     */
    public function reset(Site $site)
    {
        return (new ResetDeploymentStatusCommand())->for($site);
    }

    /**
     * Get latest deployment log from given site.
     *
     * @param \Laravel\Forge\Sites\Site $site
     *
     * @return string
     */
    public function log(Site $site)
    {
        return (new GetDeploymentLogCommand())->for($site);
    }
}
