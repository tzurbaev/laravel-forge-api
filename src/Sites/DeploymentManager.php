<?php

namespace Laravel\Forge\Sites;

use Laravel\Forge\Sites\Commands\DeployCommand;
use Laravel\Forge\Sites\Commands\EnableDeploymentCommand;
use Laravel\Forge\Sites\Commands\GetDeploymentLogCommand;
use Laravel\Forge\Sites\Commands\DisableDeploymentCommand;
use Laravel\Forge\Sites\Commands\GetDeploymentScriptCommand;
use Laravel\Forge\Sites\Commands\ResetDeploymentStatusCommand;
use Laravel\Forge\Sites\Commands\UpdateDeploymentScriptCommand;

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
        return (new EnableDeploymentCommand())
            ->for($site)
            ->on($site->getServer());
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
        return (new DisableDeploymentCommand())
            ->for($site)
            ->on($site->getServer());
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
        return (new GetDeploymentScriptCommand())
            ->for($site)
            ->from($site->getServer());
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
            ->for($site)
            ->withPayload(['content' => $script])
            ->on($site->getServer());
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
        return (new DeployCommand())
            ->for($site)
            ->on($site->getServer());
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
        return (new ResetDeploymentStatusCommand())
            ->for($site)
            ->on($site->getServer());
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
        return (new GetDeploymentLogCommand())
            ->for($site)
            ->from($site->getServer());
    }
}
