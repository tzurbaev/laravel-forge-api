<?php

namespace Laravel\Forge\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Laravel\Forge\Forge;

class ForgeFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return Forge::class;
    }
}
