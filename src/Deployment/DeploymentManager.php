<?php

namespace Laravel\Forge\Deployment;

use Laravel\Forge\Deployment\Commands\DeployCommand;
use Laravel\Forge\Deployment\Commands\EnableDeploymentCommand;
use Laravel\Forge\Deployment\Commands\GetDeploymentLogCommand;
use Laravel\Forge\Deployment\Commands\DisableDeploymentCommand;
use Laravel\Forge\Deployment\Commands\GetDeploymentScriptCommand;
use Laravel\Forge\Deployment\Commands\ResetDeploymentStatusCommand;
use Laravel\Forge\Deployment\Commands\UpdateDeploymentScriptCommand;

class DeploymentManager
{
    /**
     * Enable quick deployment on given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\EnableDeploymentCommand
     */
    public function enable()
    {
        return (new EnableDeploymentCommand());
    }

    /**
     * Disable quick deployment on given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\DisableDeploymentCommand
     */
    public function disable()
    {
        return (new DisableDeploymentCommand());
    }

    /**
     * Get deployment script from given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\GetDeploymentScriptCommand
     */
    public function script()
    {
        return (new GetDeploymentScriptCommand());
    }

    /**
     * Update deployment script for given site.
     *
     * @param string $script
     *
     * @return \Laravel\Forge\Deployment\Commands\UpdateDeploymentScriptCommand
     */
    public function updateScript(string $script)
    {
        return (new UpdateDeploymentScriptCommand())->withPayload(['content' => $script]);
    }

    /**
     * Perform deployment of given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\DeployCommand
     */
    public function deploy()
    {
        return (new DeployCommand());
    }

    /**
     * Reset deployment status for given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\ResetDeploymentStatusCommand
     */
    public function reset()
    {
        return (new ResetDeploymentStatusCommand());
    }

    /**
     * Get latest deployment log from given site.
     *
     * @return \Laravel\Forge\Deployment\Commands\GetDeploymentLogCommand
     */
    public function log()
    {
        return (new GetDeploymentLogCommand());
    }
}
