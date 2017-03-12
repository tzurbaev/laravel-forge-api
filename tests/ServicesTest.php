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
    public function testInstallService(ServiceContract $service, array $payload, Server $server, $expectedResult, bool $exception = false)
    {
        $services = new ServicesManager();

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $services->install($service)->withPayload($payload)->on($server);
        $this->assertSame($expectedResult, $result);
    }

    public static function fakeServer(Closure $apiCallback = null): Server
    {
        $api = Api::fake($apiCallback);
        $server = new Server($api, Api::serverData());

        return $server;
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
                'server' => static::fakeServer(function ($http) {
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
                'server' => static::fakeServer(function ($http) {
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
                'server' => static::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new NginxService(),
                'payload' => [],
                'server' => static::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
            [
                'service' => new PostgresService(),
                'payload' => [],
                'server' => static::fakeServer(),
                'expectedResult' => false,
                'exception' => true,
            ],
        ];
    }
}
