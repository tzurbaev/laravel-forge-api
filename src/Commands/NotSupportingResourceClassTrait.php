<?php

namespace Laravel\Forge\Commands;

use LogicException;

trait NotSupportingResourceClassTrait
{
    /**
     * Server resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        throw new LogicException(get_class($this).' does not support resource classes.');
    }
}
