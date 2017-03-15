<?php

namespace Laravel\Forge\Certificates\Commands;

use Laravel\Forge\Certificates\Certificate;
use Laravel\Forge\Commands\ResourceCommand;

abstract class CertificateCommand extends ResourceCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'certificates';
    }

    /**
     * Resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return Certificate::class;
    }

    /**
     * Certificate operation type.
     *
     * @param string $type
     *
     * @return static
     */
    public function operationType(string $type)
    {
        return $this->attachPayload('type', $type);
    }
}
