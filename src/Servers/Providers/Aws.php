<?php

namespace Laravel\Forge\Servers\Providers;

class Aws extends AbstractProvider
{
    /**
     * @{inheritdoc}
     */
    public function provider()
    {
        return 'aws';
    }

    /**
     * @{inheritdoc}
     */
    public function regions()
    {
        return [
            'us-west-1' => 'California',
            'eu-west-1' => 'Ireland',
            'eu-central-1' => 'Frankfurt',
            'ap-south-1' => 'Mumbai',
            'us-west-2' => 'Oregon',
            'sa-east-1' => 'Sao Paolo',
            'ap-northeast-2' => 'Seoul',
            'ap-southeast-1' => 'Singapore',
            'ap-southeast-2' => 'Sydney',
            'ap-northeast-1' => 'Tokyo',
            'us-east-1' => 'Virginia',
        ];
    }

    /**
     * @{inheritdoc}
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
            '32GB' => 32,
            '64GB' => 60,
        ];
    }
}
