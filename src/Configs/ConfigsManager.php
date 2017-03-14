<?php

namespace Laravel\Forge\Configs;

use Laravel\Forge\Configs\Commands\GetConfigFileCommand;
use Laravel\Forge\Configs\Commands\UpdateConfigFileCommand;

class ConfigsManager
{
    /**
     * Initialize new get configuration command.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Configs\Commands\GetConfigFileCommand
     */
    public function get(string $name)
    {
        return (new GetConfigFileCommand())->usingFile($name);
    }

    /**
     * Initialize new update configuration command.
     *
     * @param string $name
     * @param string $content
     *
     * @return \Laravel\Forge\Configs\Commands\UpdateConfigFileCommand
     */
    public function update(string $name, string $content)
    {
        return (new UpdateConfigFileCommand())
            ->usingFile($name)
            ->withPayload(['content' => $content]);
    }
}
