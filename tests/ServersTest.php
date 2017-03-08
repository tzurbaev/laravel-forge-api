<?php

namespace Laravel\Tests\Forge;

use Laravel\Forge\ForgeServers;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class ServersTests extends TestCase
{
    /**
     * @dataProvider serversListDataProvider
     */
    public function testServersListCanBeRetrieved(array $json)
    {
        // Servers List can be retrieved via API.

        // Create API provider.
        // Create Servers manager.
        // Fetch servers list.

        // Assert that servers list contains expected servers.

        $api = Api::fake(function ($http) use ($json) {
            $http->shouldReceive('request', 'GET', '/api/v1/servers')
                ->andReturn(
                    FakeResponse::fake()->withJson($json)->toResponse()
                );
        });

        $servers = new ForgeServers($api);

        $jsonServers = $json['servers'];

        foreach ($jsonServers as $jsonServer) {
            $server = $servers[$jsonServer['name']];

            $this->assertSame($jsonServer['name'], $server['name']);
        }
    }

    public function serversListDataProvider(): array
    {
        return [
            [
                'json' => [
                    'servers' => [
                        [
                            'id' => 1,
                            'name' => 'northrend',
                            'size' => '512MB',
                            'region' => 'Amsterdam 2',
                            'php_version' => 'php7.1',
                            'ip_address' => '37.139.3.148',
                            'private_ip_address' => '10.129.3.252',
                            'blackfire_status' => null,
                            'papertail_status' => null,
                            'revoked' => false,
                            'created_at' => '2016-12-15 18:38:18',
                            'is_ready' => true,
                            'network' => [],
                        ],
                        [
                            'id' => 2,
                            'name' => 'azeroth',
                            'size' => '512MB',
                            'region' => 'Amsterdam 2',
                            'php_version' => 'php7.1',
                            'ip_address' => '37.139.3.149',
                            'private_ip_address' => '10.129.3.253',
                            'blackfire_status' => null,
                            'papertail_status' => null,
                            'revoked' => false,
                            'created_at' => '2016-12-15 18:38:19',
                            'is_ready' => true,
                            'network' => [],
                        ],
                    ],
                ],
            ],
        ];
    }
}
