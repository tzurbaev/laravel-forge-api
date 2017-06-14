<?php

namespace Laravel\Tests\Forge;

use Laravel\Forge\Forge;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Tests\Forge\Helpers\FakeRateLimiter;

class RateLimitTest extends TestCase
{

    /**
     * @dataProvider rateLimitingProvider
     */
    public function testRateLimiting(Forge $forge, FakeRateLimiter $limiter)
    {

        // We use a fake rate limiting class method
        // which in this case simply increments a value

        // Set the function as our rate limiter
        $forge->setRateLimiter([$limiter, 'limit']);

        // check that no requests have been rate limited yet
        $this->assertEquals(0, $limiter->getLimiterCount());

        // make a few requests
        $credentials = $forge->credentials();
        $credentials = $forge->credentials();

        // check that the requests have run the rate limiting closure
        $this->assertEquals(2, $limiter->getLimiterCount());
    }

    public function rateLimitingProvider(): array
    {
        $api = Api::fake(function ($http) {
            $http->shouldReceive('request')
                ->with('GET', 'credentials')
                ->andReturn(
                    FakeResponse::fake()
                        ->withJson([
                            'credentials' => [
                                [
                                    'id' => 1,
                                    'type' => 'ocean2',
                                    'name' => 'Personal',
                                ]
                            ],
                        ])
                        ->toResponse()
                );
        });

        return [
            [
                'forge' => new Forge($api),
                'limiter' => new FakeRateLimiter(),
            ]
        ];
    }
}
