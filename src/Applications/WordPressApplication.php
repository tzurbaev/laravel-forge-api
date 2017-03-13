<?php

namespace Laravel\Forge\Applications;

use Laravel\Forge\Contracts\ApplicationContract;

class WordPressApplication extends Application implements ApplicationContract
{
    /**
     * Application type.
     *
     * @return string
     */
    public function type()
    {
        return 'wordpress';
    }

    /**
     * Set application database and user.
     *
     * @param string $database
     * @param string $user
     *
     * @return static
     */
    public function usingDatabase(string $database, string $user)
    {
        $this->payload = [
            'database' => $database,
            'user' => $user,
        ];

        return $this;
    }
}
