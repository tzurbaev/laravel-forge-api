<?php

namespace Laravel\Forge\Daemons\Commands;

class CreateDaemonCommand extends DaemonCommand
{
    /**
     * Set the command.
     *
     * @param string $command
     *
     * @return static
     */
    public function start(string $command)
    {
        return $this->attachPayload('command', $command);
    }

    /**
     * Set the user of command.
     *
     * @param string $user
     *
     * @return static
     */
    public function runningAs(string $user)
    {
        return $this->attachPayload('user', $user);
    }
}
