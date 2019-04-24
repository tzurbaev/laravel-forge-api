<?php

namespace Laravel\Forge\Users\Commands;

use Laravel\Forge\Commands\ResourceCommand;
use Laravel\Forge\Users\User;

class GetAuthenticatedUserCommand extends ResourceCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'user';
    }

    /**
     * Resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return User::class;
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        return 'GET';
    }
}
