<?php

namespace Laravel\Forge\Contracts;

interface ServiceContract
{
    /**
     * Service name.
     *
     * @return string
     */
    public function name();

    /**
     * Determines if service can be installed on server.
     *
     * @return bool
     */
    public function installable();

    /**
     * Determines if service can be uninstalled from server.
     *
     * @return bool
     */
    public function uninstallable();

    /**
     * Determines if service can be rebooted.
     *
     * @return bool
     */
    public function rebootable();

    /**
     * Determines if service can be stopped.
     *
     * @return bool
     */
    public function stoppable();
}
