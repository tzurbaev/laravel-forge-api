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
    public function testCreateServer(array $payload, array $response, Closure $factory)
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

        $this->assertSame($payload['name'], $server->name());
        $this->assertSame($payload['size'], $server->size());
        $this->assertSame($payload['php_version'], $server->phpVersion());
        $this->assertFalse($server->isReady());
    }

    public function payload($provider = 'ocean2', $size = '512MB', $region = 'fra1')
    {
        return [
            'credential_id' => 1,
            'database' => 'laravel',
            'load_balancer' => 1,
            'maria' => 1,
            'name' => 'northrend',
            'network' => [1, 2, 3],
            'php_version' => 'php71',
            'provider' => $provider,
            'region' => $region,
            'size' => $size,
        ];
    }

    public function response($size = '512MB', $region = 'Frankfurt')
    {
        return [
            'id' => 1,
            'credential_id' => 1,
            'name' => 'northrend',
            'size' => $size,
            'region' => $region,
            'php_version' => 'php71',
            'ip_address' => '37.139.3.148',
            'private_ip_address' => '10.129.3.252',
            'blackfire_status' => null,
            'papertail_status' => null,
            'revoked' => false,
            'created_at' => '2016-12-15 18:38:18',
            'is_ready' => false,
            'network' => [1, 2, 3],
        ];
    }

    public function createServerDataProvider(): array
    {
        return [
            [
                'payload' => $this->payload(),
                'response' => $this->response(),
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
                        ->save();
                },
            ],
            [
                'payload' => $this->payload('linode', '1GB', 1),
                'response' => $this->response('1GB', 'Frankfurt'),
                'factory' => function (ForgeServers $servers) {
                    return $servers
                        ->create()
                        ->linode('northrend')
                        ->withMemoryOf('1GB')
                        ->usingCredential(1)
                        ->at(1)
                        ->runningPhp('7.1')
                        ->withMariaDb('laravel')
                        ->asLoadBalancer()
                        ->connectedTo([1, 2, 3])
                        ->save();
                },
            ],
            [
                'payload' => $this->payload('aws', '1GB', 'us-west-1'),
                'response' => $this->response('1GB', 'us-west-1'),
                'factory' => function (ForgeServers $servers) {
                    return $servers
                        ->create()
                        ->aws('northrend')
                        ->withMemoryOf('1GB')
                        ->usingCredential(1)
                        ->at('us-west-1')
                        ->runningPhp('7.1')
                        ->withMariaDb('laravel')
                        ->asLoadBalancer()
                        ->connectedTo([1, 2, 3])
                        ->save();
                },
            ],
            [
                'payload' => [
                    'database' => 'laravel',
                    'ip_address' => '37.139.3.148',
                    'maria' => 1,
                    'name' => 'northrend',
                    'php_version' => 'php71',
                    'private_ip_address' => '10.129.3.252',
                    'provider' => 'custom',
                    'size' => '5GB',
                ],
                'response' => [
                    'id' => 1,
                    'credential_id' => 0,
                    'name' => 'northrend',
                    'size' => '5GB',
                    'php_version' => 'php71',
                    'ip_address' => '37.139.3.148',
                    'private_ip_address' => '10.129.3.252',
                    'blackfire_status' => null,
                    'papertail_status' => null,
                    'revoked' => false,
                    'created_at' => '2016-12-15 18:38:18',
                    'is_ready' => false,
                    'network' => [],
                ],
                'factory' => function (ForgeServers $servers) {
                    return $servers
                        ->create()
                        ->custom('northrend')
                        ->withMemoryOf('5GB')
                        ->runningPhp('7.1')
                        ->withMariaDb('laravel')
                        ->usingPublicIp('37.139.3.148')
                        ->usingPrivateIp('10.129.3.252')
                        ->save();
                },
            ],
        ];
    }
}
