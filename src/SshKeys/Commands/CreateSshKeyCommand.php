<?php

namespace Laravel\Forge\SshKeys\Commands;

class CreateSshKeyCommand extends SshKeyCommand
{
    /**
     * Set key name.
     *
     * @param string $name
     *
     * @return static
     */
    public function identifiedAs(string $name)
    {
        return $this->attachPayload('name', $name);
    }

    /**
     * Set key content.
     *
     * @param string $content
     *
     * @return static
     */
    public function withContent(string $content)
    {
        return $this->attachPayload('key', $content);
    }
}
