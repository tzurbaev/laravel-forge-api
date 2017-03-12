<?php

namespace Laravel\Tests\Forge;

use Closure;
use Mockery;
use Laravel\Forge\Server;
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
    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    /**
     * @dataProvider installServiceDataProvider
     */
    public function testInstallService(ServiceContract $service, array $payload, $server, $expectedResult, bool $exception = false)
    {
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
        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->stop($service)->on($server);
        $this->assertSame($expectedResult, $result);
    }

    public static function fakeServer(Closure $apiCallback = null, array $replaceServerData = []): Server
    {
        $api = Api::fake($apiCallback);
        $server = new Server($api, Api::serverData($replaceServerData));

        return $server;
    }

    public function multipleFakeServers(int $number, Closure $callback = null)
    {
        $servers = [];

        for ($i = 0; $i < $number; ++$i) {
            $servers[] = $this->fakeServer(
                function ($http) use ($i, $callback) {
                    if (!is_null($callback)) {
                        $callback($http, $i + 1);
                    }
                },
                [
                    'id' => $i + 1,
                    'name' => 'server'.($i + 1),
                ]
            );
        }

        return $servers;
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
                'server' => $this->fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/blackfire/install', [
                            'form_params' => [
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
                'server' => $this->fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/papertrail/install', [
                            'form_params' => ['host' => '192.241.143.108']
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new MysqlService(),
                'payload' => [],
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new NginxService(),
                'payload' => [],
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PostgresService(),
                'payload' => [],
                'server' => $this->fakeServer(),
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
                'server' => $this->fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/blackfire/remove', ['form_params' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => $this->fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/papertrail/remove', ['form_params' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new NginxService(),
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PostgresService(),
                'server' => $this->fakeServer(),
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
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => $this->fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => $this->fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/mysql/'.$command, ['form_params' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new NginxService(),
                'server' => $this->fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/nginx/'.$command, ['form_params' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
            [
                'service' => new PostgresService(),
                'server' => $this->fakeServer(function ($http) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/1/postgres/'.$command, ['form_params' => []])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],

            // Multiple servers.
            [
                'service' => new BlackfireService(),
                'server' => $this->multipleFakeServers(3),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PapertrailService(),
                'server' => $this->multipleFakeServers(3),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new MysqlService(),
                'server' => $this->multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/'.$serverId.'/mysql/'.$command, ['form_params' => []])
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
                'server' => $this->multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/'.$serverId.'/nginx/'.$command, ['form_params' => []])
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
                'server' => $this->multipleFakeServers(3, function ($http, $serverId) use ($command) {
                    $http->shouldReceive('request')
                        ->with('POST', '/api/v1/servers/'.$serverId.'/postgres/'.$command, ['form_params' => []])
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
