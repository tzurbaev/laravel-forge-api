<?php

namespace Laravel\Tests\Forge\Helpers;

use Mockery;
use Closure;
use GuzzleHttp\Client;
use Laravel\Forge\ApiProvider;

class Api
{
    /**
     * Creates fake API Provider.
     *
     * @param \Closure $callback
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public static function fake(Closure $callback)
    {
        $api = Mockery::mock(ApiProvider::class.'[getClient]', ['api-token']);
        $http = Mockery::mock(Client::class);

        $callback($http);

        $api->shouldReceive('getClient')->andReturn($http);

        return $api;
    }
}
