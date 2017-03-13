<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Server;
use PHPUnit\Framework\TestCase;
use Laravel\Forge\Daemons\Daemon;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Daemons\DaemonsManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class DaemonsTest extends TestCase
{
    protected $command = 'php /home/forge/default/daemon.php';

    /**
     * @dataProvider createDaemonDataProvider
     */
    public function testCreateDaemon($server, array $payload, Closure $assertion)
    {
        $daemons = new DaemonsManager();

        $result = $daemons->create($payload['command'])
            ->runningAs($payload['user'])
            ->on($server);

        $assertion($result);
    }

    /**
     * @dataProvider listDaemonsDataProvider
     */
    public function testListDaemons($server, Closure $assertion)
    {
        $daemons = new DaemonsManager();

        $result = $daemons->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getDaemonDataProvider
     */
    public function testGetDaemon($server, int $daemonId, Closure $assertion)
    {
        $daemons = new DaemonsManager();

        $result = $daemons->get($daemonId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider restartDaemonDataProvider
     */
    public function testRestartDaemon(Daemon $daemon, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $daemon->restart();

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider deleteDaemonDataProvider
     */
    public function testDeleteDaemon(Daemon $daemon, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $daemon->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function response(array $replace = [])
    {
        return array_merge([
            'id' => 1,
            'command' => $this->command,
            'user' => 'forge',
            'status' => 'installing',
            'created_at' => '2016-12-16 15:46:22',
        ], $replace);
    }

    public function fakeDaemon(Closure $callback = null, array $replace = []): Daemon
    {
        $server = Api::fakeServer($callback);

        return new Daemon($server->getApi(), $this->response($replace), $server);
    }

    public function createDaemonDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/daemons', [
                            'form_params' => [
                                'command' => $this->command,
                                'user' => 'forge',
                            ]
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['daemon' => $this->response()])->toResponse()
                        );
                }),
                'payload' => [
                    'command' => $this->command,
                    'user' => 'forge',
                ],
                'assertion' => function ($daemon) {
                    $this->assertSame($this->command, $daemon->command());
                    $this->assertSame('forge', $daemon->user());
                },
            ],
        ];
    }

    public function listDaemonsDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/daemons', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'daemons' => [
                                        $this->response(['id' => 1]),
                                        $this->response(['id' => 2]),
                                        $this->response(['id' => 3]),
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertInternalType('array', $result);

                    foreach ($result as $daemon) {
                        $this->assertInstanceOf(Daemon::class, $daemon);
                        $this->assertSame($this->command, $daemon->command());
                        $this->assertSame('forge', $daemon->user());
                    }
                },
            ],
        ];
    }

    public function getDaemonDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/daemons/1', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['daemon' => $this->response()])->toResponse()
                        );
                }),
                'daemonId' => 1,
                'assertion' => function ($daemon) {
                    $this->assertInstanceOf(Daemon::class, $daemon);
                    $this->assertSame($this->command, $daemon->command());
                    $this->assertSame('forge', $daemon->user());
                },
            ],
        ];
    }

    public function deleteDaemonDataProvider()
    {
        return [
            [
                'daemon' => $this->fakeDaemon(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/daemons/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }

    public function restartDaemonDataProvider()
    {
        return [
            [
                'daemon' => $this->fakeDaemon(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/daemons/1/restart')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
