<?php

namespace Laravel\Tests\Forge;

use Mockery;
use Laravel\Forge\Server;
use Laravel\Forge\Forge;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class ServersTests extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @dataProvider serversListDataProvider
     */
    public function testListServers(array $serversList)
    {
        // Servers List can be retrieved via API.

        // Create API provider.
        // Create Servers manager.
        // Fetch servers list.

        // Assert that servers list contains expected servers.

        $api = Api::fake(function ($http) use ($serversList) {
            $http->shouldReceive('request')
                ->with('GET', 'servers')
                ->andReturn(
                    FakeResponse::fake()->withJson($serversList)->toResponse()
                );
        });

        $forge = new Forge($api);
        $jsonServers = $serversList['servers'];

        foreach ($jsonServers as $jsonServer) {
            $server = $forge[$jsonServer['name']];

            $this->assertInstanceOf(Server::class, $server);
            $this->assertSame($jsonServer['name'], $server->name());
        }
    }

    /**
     * @dataProvider getServerDataProvider
     */
    public function testGetServer(array $data)
    {
        // Single server can be retrieved via API.

        // Create API provider.
        // Create Servers manager.
        // Load single server by ID.

        // Assert that server was loaded.

        $api = Api::fake(function ($http) use ($data) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$data['id'])
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $data])->toResponse()
                );
        });

        $forge = new Forge($api);
        $server = $forge->get($data['id']);

        $this->assertInstanceOf(Server::class, $server);
        $this->assertSame($data['name'], $server->name());
        $this->assertSame($data['provider'], $server->provider());
        $this->assertSame($data['provider_id'], $server->providerId());
        $this->assertSame($data['ssh_port'], $server->sshPort());
        $this->assertSame($data['tags'], $server->tags());
        $this->assertNull($server->databasePassword());
        $this->assertNull($server->sudoPassword());
    }

    /**
     * @dataProvider getServerDataProvider
     */
    public function testReloadServer(array $data)
    {
        // Single server is retrieved from the server cache.

        // Create API provider.
        // Create Servers manager.
        // Load single server by ID.
        // Reload single server by ID, from cache
        // Force reload single server by ID

        // Assert that the same server was loaded each time
        // Assert that only two requests were made

        $api = Api::fake(function ($http) use ($data) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$data['id'])
                ->twice()
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $data])->toResponse()
                );
        });

        $forge = new Forge($api);
        $server = $forge->get($data['id']);
        $serverReload = $forge->get($data['id'], false);
        $serverReloadWithRefresh = $forge->get($data['id'], true);
        //
        $this->assertInstanceOf(Server::class, $server);
        $this->assertInstanceOf(Server::class, $serverReload);
        $this->assertInstanceOf(Server::class, $serverReloadWithRefresh);
        $this->assertSame($server->name(), $serverReload->name());
        $this->assertSame($server->name(), $serverReloadWithRefresh->name());
    }

    /**
     * @dataProvider getServerFailDataProvider
     */
    public function testGetServerFail(int $serverId)
    {
        // If single server was not found, exception should be thrown.

        // Create API provider.
        // Create Servers manager.
        // Load single non-existed server by ID.

        // Assert that ServerWasNotFoundException was thrown.

        $api = Api::fake(function ($http) use ($serverId) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$serverId)
                ->andReturn(FakeResponse::fake()->withStatus(404)->toResponse());
        });

        $forge = new Forge($api);

        $this->expectException(ServerWasNotFoundException::class);
        $server = $forge->get($serverId);
    }

    /**
     * @dataProvider updateServerDataProvider
     */
    public function testUpdateServer(array $data, array $payload, array $response)
    {
        // Server data can be updated via API.

        // Create API provider.
        // Create Servers manager.
        // Load single server by ID.
        // Update server data.

        // Assert that server was updated.

        $api = Api::fake(function ($http) use ($data, $payload, $response) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$data['id'])
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $data])->toResponse()
                );

            $http->shouldReceive('request')
                ->with('PUT', 'servers/'.$data['id'], ['json' => $payload])
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $response])->toResponse()
                );
        });

        $forge = new Forge($api);
        $server = $forge->get($data['id']);

        $this->assertTrue($server->update($payload));

        foreach ($payload as $field => $value) {
            $this->assertSame($value, $server[$field]);
        }
    }

    /**
     * @dataProvider deleteServerDataProvider
     */
    public function testDeleteServer(array $data)
    {
        // Server can be deleted via API.

        // Create API provider.
        // Create Servers manager.
        // Load single server by ID.
        // Delete server.

        // Assert that server was deleted.

        $api = Api::fake(function ($http) use ($data) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$data['id'])
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $data])->toResponse()
                );

            $http->shouldReceive('request')
                ->with('DELETE', 'servers/'.$data['id'])
                ->andReturn(
                    FakeResponse::fake()->toResponse()
                );
        });

        $forge = new Forge($api);
        $server = $forge->get($data['id']);

        $this->assertTrue($server->delete());
    }

    /**
     * @dataProvider serverOperationsDataProvider
     */
    public function testServerOperations(string $method, array $data, array $operation, array $response, $expectedResult)
    {
        // Additional operations can be performed on servers.

        // Create API provider.
        // Create Servers manager.
        // Load single server by ID.
        // Perform operation on server.

        // Assert that operation results equals to expected result.

        $api = Api::fake(function ($http) use ($data, $operation, $response) {
            $http->shouldReceive('request')
                ->with('GET', 'servers/'.$data['id'])
                ->andReturn(
                    FakeResponse::fake()->withJson(['server' => $data])->toResponse()
                );

            $http->shouldReceive('request')
                ->with($operation['method'], $operation['url'])
                ->andReturn(
                    FakeResponse::fake()->withJson($response)->toResponse()
                );
        });

        $forge = new Forge($api);
        $server = $forge->get($data['id']);

        $this->assertSame($expectedResult, $server->{$method}());
    }

    public function serversListDataProvider(): array
    {
        return [
            [
                'json' => [
                    'servers' => [
                        Api::serverData(),
                        Api::serverData([
                            'id' => 2,
                            'name' => 'azeroth',
                            'ip_address' => '37.139.3.149',
                            'private_ip_address' => '10.129.3.253',
                            'created_at' => '2016-12-15 18:38:19',
                        ]),
                    ],
                ],
            ],
        ];
    }

    public function getServerDataProvider(): array
    {
        return [
            [
                'data' => Api::serverData(),
            ],
        ];
    }

    public function getServerFailDataProvider(): array
    {
        return [
            [
                'serverId' => 1,
            ],
        ];
    }

    public function updateServerDataProvider(): array
    {
        return [
            [
                'data' => Api::serverData(),
                'payload' => [
                    'name' => 'azeroth',
                    'network' => [1, 2],
                    'private_ip_address' => '10.10.10.10',
                ],
                'response' => Api::serverData([
                    'name' => 'azeroth',
                    'network' => [1, 2],
                    'private_ip_address' => '10.10.10.10',
                ]),
            ],
        ];
    }

    public function deleteServerDataProvider(): array
    {
        return [
            [
                'data' => Api::serverData(),
            ],
        ];
    }

    public function serverOperationsDataProvider(): array
    {
        return [
            [
                'method' => 'reboot',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'POST',
                    'url' => 'servers/1/reboot',
                ],
                'response' => [],
                'expectedResult' => true,
            ],
            [
                'method' => 'enableOPCache',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'POST',
                    'url' => 'servers/1/php/opcache',
                ],
                'response' => [],
                'expectedResult' => true,
            ],
            [
                'method' => 'disableOPCache',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'DELETE',
                    'url' => 'servers/1/php/opcache',
                ],
                'response' => [],
                'expectedResult' => true,
            ],
            [
                'method' => 'revokeAccess',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'POST',
                    'url' => 'servers/1/revoke',
                ],
                'response' => [],
                'expectedResult' => true,
            ],
            [
                'method' => 'reconnect',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'POST',
                    'url' => 'servers/1/reconnect',
                ],
                'response' => [
                    'public_key' => 'secret',
                ],
                'expectedResult' => 'secret',
            ],
            [
                'method' => 'reactivate',
                'data' => Api::serverData(),
                'operation' => [
                    'method' => 'POST',
                    'url' => 'servers/1/reactivate',
                ],
                'response' => [],
                'expectedResult' => true,
            ],
        ];
    }
}
