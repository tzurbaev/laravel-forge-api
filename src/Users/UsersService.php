<?php

namespace Laravel\Forge\Users;

use Laravel\Forge\Users\Commands\GetAuthenticatedUserCommand;

class UsersService
{
    public function get()
    {
        return (new GetAuthenticatedUserCommand());
    }
}
