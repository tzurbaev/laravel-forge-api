<?php

namespace Laravel\Forge\Contracts;

interface ApplicationContract
{
    /**
     * Application type.
     *
     * @return string
     */
    public function type();

    /**
     * Application request payload.
     *
     * @return array
     */
    public function payload();
}
