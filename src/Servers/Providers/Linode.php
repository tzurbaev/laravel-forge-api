<?php

namespace Laravel\Forge\Servers\Providers;

class Linode extends Provider
{
    /**
     * {@inheritdoc}
     */
    public function provider()
    {
        return 'linode';
    }

    /**
     * {@inheritdoc}
     */
    public function regions()
    {
        return [
            2 => 'Dallas',
            3 => 'Fremont',
            4 => 'Atlanta',
            6 => 'Newark',
            7 => 'London',
            8 => 'Tokyo 1',
            9 => 'Singapore',
            10 => 'Frankfurt',
            11 => 'Tokyo 2',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function sizes()
    {
        return [
            '1GB' => 1,
            '2GB' => 2,
            '4GB' => 4,
            '8GB' => 8,
            '12GB' => 12,
            '16GB' => 16,
            '32GB' => 32,
            '60GB' => 60,
            '100GB' => 100,
            '200GB' => 200,
        ];
    }
}
