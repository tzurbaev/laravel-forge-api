<?php

namespace Laravel\Forge\SshKeys;

use Laravel\Forge\SshKeys\Commands\GetSshKeyCommand;
use Laravel\Forge\SshKeys\Commands\ListSshKeysCommand;
use Laravel\Forge\SshKeys\Commands\CreateSshKeyCommand;

class SshKeysManager
{
    /**
     * Initialize new create SSH key command.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\SshKeys\Commands\CreateSshKeyCommand
     */
    public function create(string $name)
    {
        return (new CreateSshKeyCommand())->identifiedAs($name);
    }

    /**
     * Initialize new list SSH keys command.
     *
     * @return \Laravel\Forge\SshKeys\Commands\ListSshKeysCommand
     */
    public function list()
    {
        return new ListSshKeysCommand();
    }

    /**
     * Initialize new get SSH key command.
     *
     * @param int $keyId
     *
     * @return \Laravel\Forge\SshKeys\Commands\GetSshKeyCommand
     */
    public function get(int $keyId)
    {
        return (new GetSshKeyCommand())->setItemId($keyId);
    }
}
