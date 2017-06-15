<?php

namespace Laravel\Tests\Forge\Helpers;

use Laravel\Forge\Commands\ResourceCommand;

class FakeResourceCommand extends ResourceCommand
{
    public function resourcePath()
    {
        return 'foo';
    }

    public function resourceClass()
    {
        return 'FakeResource';
    }

    public function listResponseItemsKey()
    {
        return 'bar';
    }
}
