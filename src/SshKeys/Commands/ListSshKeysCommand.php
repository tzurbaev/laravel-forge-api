<?php

namespace Laravel\Forge\SshKeys\Commands;

class ListSshKeysCommand extends SshKeyCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'keys';
    }
}
