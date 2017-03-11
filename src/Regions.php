<?php

namespace Laravel\Forge;

class Regions
{
    /**
     * @var array
     */
    protected static $regions = [
        'ocean2' => [
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
        ],
    ];

    /**
     * This class can not be instantiated.
     */
    private function __construct()
    {
        //
    }

    /**
     * Determines if given region is available at given provider.
     *
     * @param string $region
     * @param string $provider
     *
     * @return bool
     */
    public static function available(string $region, string $provider): bool
    {
        if (!isset(static::$regions[$provider])) {
            return false;
        }

        return isset(static::$regions[$provider][$region]) || in_array($region, static::$regions[$provider]);
    }
}
