<?php

namespace Laravel\Forge\Servers\Providers;

class DigitalOcean extends Provider
{
    /**
     * {@inheritdoc}
     */
    public function provider()
    {
        return 'ocean2';
    }

    /**
     * {@inheritdoc}
     */
    public function regions()
    {
        return [
            'ams2' => 'Amsterdam 2',
            'ams3' => 'Amsterdam 3',
            'blr1' => 'Bangalore',
            'lon1' => 'London',
            'fra1' => 'Frankfurt',
            'nyc1' => 'New York 1',
            'nyc2' => 'New York 2',
            'nyc3' => 'New York 3',
            'sfo1' => 'San Francisco 1',
            'sfo2' => 'San Francisco 2',
            'sgp1' => 'Singapore',
            'tor1' => 'Toronto',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sizes()
    {
        return [
            '512MB' => 512,
            '1GB' => 1,
            '2GB' => 2,
            '4GB' => 4,
            '8GB' => 8,
            '16GB' => 16,
            'm-16GB' => 'm16',
            '32GB' => 32,
            'm-32GB' => 'm32',
            '64GB' => 64,
            'm-64GB' => 'm-64',
        ];
    }
}
