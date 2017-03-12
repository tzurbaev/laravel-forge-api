<?php

namespace Laravel\Forge\Services;

use Laravel\Forge\Contracts\ServiceContract;
use Laravel\Forge\Services\Commands\StopServiceCommand;
use Laravel\Forge\Services\Commands\RebootServiceCommand;
use Laravel\Forge\Services\Commands\InstallServiceCommand;
use Laravel\Forge\Services\Commands\UninstallServiceCommand;

class ServicesManager
{
    /**
     * Install the service.
     *
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     *
     * @return \Laravel\Forge\Services\Commands\InstallServiceCommand
     */
    public function install(ServiceContract $service)
    {
        return new InstallServiceCommand($service);
    }

    /**
     * Reboot the service.
     *
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     *
     * @return \Laravel\Forge\Services\Commands\RebootServiceCommand
     */
    public function reboot(ServiceContract $service)
    {
        return new RebootServiceCommand($service);
    }

    /**
     * Stop the service.
     *
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     *
     * @return \Laravel\Forge\Services\Commands\StopServiceCommand
     */
    public function stop(ServiceContract $service)
    {
        return new StopServiceCommand($service);
    }

    /**
     * Uninstall the service.
     *
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     *
     * @return \Laravel\Forge\Services\Commands\UninstallServiceCommand
     */
    public function uninstall(ServiceContract $service)
    {
        return new UninstallServiceCommand($service);
    }
}
