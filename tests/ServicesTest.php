<?php

namespace Laravel\Tests\Forge;

use Mockery;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Services\MysqlService;
use Laravel\Forge\Services\NginxService;
use Laravel\Forge\Services\PostgresService;
use Laravel\Forge\Services\ServicesManager;
use Laravel\Forge\Contracts\ServiceContract;
use Laravel\Forge\Services\BlackfireService;
use Laravel\Forge\Services\PapertrailService;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class ServicesTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @dataProvider installServiceDataProvider
     */
    public function testInstallService(ServiceContract $service, array $payload, $server, $expectedResult, bool $exception = false)
    {
        // New services can be installed on servers.

        // Create Services manager.
        // Install service on given server.

        // Assert that exception was thrown or operation ended with result
        // equals to expected result.

        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->install($service)->withPayload($payload)->on($server);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider uninstallServiceDataProvider
     */
    public function testUninstallService(ServiceContract $service, $server, $expectedResult, bool $exception = false)
    {
        // Services can be uninstalled from servers.

        // Create Services manager.
        // Uninstall service from given server.

        // Assert that exception was thrown or operation ended with result
        // equals to expected result.

        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->uninstall($service)->from($server);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider rebootServiceDataProvider
     */
    public function testRebootService(ServiceContract $service, $server, $expectedResult, bool $exception = false)
    {
        // Services can be rebooted.

        // Create Services manager.
        // Reboot service on given server.

        // Assert that exception was thrown or operation ended with result
        // equals to expected result.

        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->reboot($service)->on($server);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider stopServiceDataProvider
     */
    public function testStopService(ServiceContract $service, $server, $expectedResult, bool $exception = false)
    {
        // Services can be stopped.

        // Create Services manager.
        // Stop service on given server.

        // Assert that exception was thrown or operation ended with result
        // equals to expected result.

        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->stop($service)->on($server);
        $this->assertSame($expectedResult, $result);
    }

    public function installServiceDataProvider(): array
    {
        return [
            [
                'service' => new BlackfireService(),
                'payload' => [
                    'server_id' => 'server-id',
                    'server_token' => 'server-token',
                ],
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/blackfire/install', [
                            'json' => [
                                'server_id' => 'server-id',
                                'server_token' => 'server-token',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new PapertrailService(),
                'payload' => ['host' => '192.241.143.108'],
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/papertrail/install', [
                            'json' => ['host' => '192.241.143.108']
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new MysqlService(),
                'payload' => [],
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new NginxService(),
                'payload' => [],
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PostgresService(),
                'payload' => [],
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
        ];
    }

    public function uninstallServiceDataProvider(): array
    {
        return [
            [
                'service' => new BlackfireService(),
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/blackfire/remove', ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/papertrail/remove', ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new NginxService(),
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PostgresService(),
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
        ];
    }

    public function rebootServiceDataProvider(): array
    {
        return $this->simpleServicesCommand('reboot');
    }

    public function stopServiceDataProvider(): array
    {
        return $this->simpleServicesCommand('stop');
    }

    public function simpleServicesCommand($command): array
    {
        return [
            // Single server.
            [
                'service' => new BlackfireService(),
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => Api::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => Api::fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/mysql/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new NginxService(),
                'server' => Api::fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/nginx/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new PostgresService(),
                'server' => Api::fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/postgres/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],

            // Multiple servers.
            [
                'service' => new BlackfireService(),
                'server' => Api::multipleFakeServers(3),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => Api::multipleFakeServers(3),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => Api::multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/'.$serverId.'/mysql/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => [
                    'server1' => true,
                    'server2' => true,
                    'server3' => true,
                ],
            ],
            [
                'service' => new NginxService(),
                'server' => Api::multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/'.$serverId.'/nginx/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => [
                    'server1' => true,
                    'server2' => true,
                    'server3' => true,
                ],
            ],
            [
                'service' => new PostgresService(),
                'server' => Api::multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/'.$serverId.'/postgres/'.$command, ['json' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => [
                    'server1' => true,
                    'server2' => true,
                    'server3' => true,
                ],
            ],
        ];
    }
}
