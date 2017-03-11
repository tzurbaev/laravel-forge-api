<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Server;
use Laravel\Forge\ForgeServers;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class CreateServerTest extends TestCase
{
    /**
     * @dataProvider createServerDataProvider
     */
    public function testCreateServer(array $payload, array $response, Closure $factory, Closure $assertion)
    {
        // Servers can be created via API.

        // Create API provider.
        // Create Servers manager.
        // Create server.

        // Assert that server was created.

        $api = Api::fake(function ($http) use ($payload, $response) {
            $http->shouldReceive('request')
                ->with('POST', '/api/v1/servers', ['form_params' => $payload])
                ->andReturn(
                    FakeResponse::fake()
                        ->withJson([
                            'server' => $response,
                            'sudo_password' => 'secret',
                            'database_password' => 'secret',
                        ])
                        ->toResponse()
                );
        });

        $servers = new ForgeServers($api);
        $server = $factory($servers);

        $assertion($server, $payload);
    }

    public function createServerDataProvider(): array
    {
        return [
            [
                'payload' => [
                    'credential_id' => 1,
                    'database' => 'laravel',
                    'load_balancer' => 1,
                    'maria' => 1,
                    'name' => 'northrend',
                    'network' => [1, 2, 3],
                    'php_version' => 'php71',
                    'provider' => 'ocean2',
                    'region' => 'fra1',
                    'size' => '512MB',
                ],
                'response' => [
                    'id' => 1,
                    'credential_id' => 1,
                    'name' => 'northrend',
                    'size' => '512MB',
                    'region' => 'Frankfurt',
                    'php_version' => 'php71',
                    'ip_address' => '37.139.3.148',
                    'private_ip_address' => '10.129.3.252',
                    'blackfire_status' => null,
                    'papertail_status' => null,
                    'revoked' => false,
                    'created_at' => '2016-12-15 18:38:18',
                    'is_ready' => false,
                    'network' => [1, 2, 3],
                ],
                'factory' => function (ForgeServers $servers) {
                    return $servers
                        ->create()
                        ->droplet('northrend')
                        ->withMemoryOf('512MB')
                        ->usingCredential(1)
                        ->at('fra1')
                        ->runningPhp('7.1')
                        ->withMariaDb('laravel')
                        ->asLoadBalancer()
                        ->connectedTo([1, 2, 3])
                        ->send();
                },
                'assertion' => function (Server $server, array $payload) {
                    $this->assertSame($payload['name'], $server->name());
                    $this->assertSame($payload['size'], $server->size());
                    $this->assertSame($payload['php_version'], $server->phpVersion());
                    $this->assertFalse($server->isReady());
                }
            ],
        ];
    }
}
