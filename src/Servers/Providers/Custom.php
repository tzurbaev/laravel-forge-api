<?php

namespace Laravel\Forge\Servers\Providers;

use InvalidArgumentException;

class Custom extends Provider
{
    /**
     * @{inheritdoc}
     */
    public function provider()
    {
        return 'custom';
    }

    /**
     * @{inheritdoc}
     */
    public function regionAvailable(string $region)
    {
        throw new InvalidArgumentException('Custom provider does not support any region.');
    }

    /**
     * @{inheritdoc}
     */
    public function memoryAvailable($memory)
    {
        return true;
    }

    public function validate()
    {
        $errors = [];

        if (empty($this->payload['ip_address'])) {
            $errors[] = 'ip_address';
        }

        if (empty($this->payload['private_ip_address'])) {
            $errors[] = 'private_ip_address';
        }

        return count($errors) > 0 ? $errors : true;
    }
}
