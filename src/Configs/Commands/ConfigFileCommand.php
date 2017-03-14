<?php

namespace Laravel\Forge\Configs\Commands;

use Laravel\Forge\Commands\ResourceCommand;

abstract class ConfigFileCommand extends ResourceCommand
{
    /**
     * Configuration file name.
     *
     * @var string
     */
    protected $configFile = '';

    /**
     * Set configuration file name.
     *
     * @param string $file
     *
     * @return static
     */
    public function usingFile(string $file)
    {
        $this->configFile = $file;

        return $this;
    }

    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return $this->configFile;
    }
}
