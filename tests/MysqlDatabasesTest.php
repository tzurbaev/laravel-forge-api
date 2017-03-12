<?php

namespace Laravel\Tests\Forge;

use Closure;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Services\MysqlService;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Forge\Services\Mysql\MysqlDatabase;

class MysqlDatabasesTest extends TestCase
{
    /**
     * @dataProvider createDatabaseDataProvider
     */
    public function testCreateDatabase($server, array $database, Closure $factory)
    {
        $mysql = new MysqlService();

        $create = $factory($mysql);
        $result = $create->on($server);

        $this->assertInstanceOf(MysqlDatabase::class, $result);

        foreach ($database as $key => $value) {
            $this->assertSame($value, $result[$key]);
        }
    }

    /**
     * @dataProvider listDatabasesDataProvider
     */
    public function testListDatabases($server, Closure $assertion)
    {
        $mysql = new MysqlService();

        $result = $mysql->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getDatabasesDataProvider
     */
    public function testGetDatabase($server, int $databaseId, Closure $assertion)
    {
        $mysql = new MysqlService();

        $result = $mysql->get($databaseId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider deleteDatabaseDataProvider
     */
    public function testDeleteDatabase(MysqlDatabase $database, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $database->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'forge',
            'status' => 'installing',
            'created_at' => '2016-12-16 16:12:22',
        ], $replace);
    }

    public function createDatabaseDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/mysql', [
                            'form_params' => ['name' => 'forge'],
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'database' => $this->response(),
                                ])
                                ->toResponse()
                        );
                }),
                'database' => ['name' => 'forge'],
                'factory' => function ($mysql) {
                    return $mysql->create('forge');
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/mysql', [
                            'form_params' => [
                                'name' => 'forge',
                                'user' => 'forge',
                                'password' => 'secret',
                            ],
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'database' => [
                                        'id' => 1,
                                        'name' => 'forge',
                                        'status' => 'installing',
                                        'created_at' => '2016-12-16 16:12:22',
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
                'database' => ['name' => 'forge'],
                'factory' => function ($mysql) {
                    return $mysql->create('forge')->withUser('forge', 'secret');
                }
            ],
        ];
    }

    public function listDatabasesDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/mysql', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'databases' => [
                                        $this->response(['id' => 1]),
                                        $this->response(['id' => 2]),
                                        $this->response(['id' => 3]),
                                        $this->response(['id' => 4]),
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertInternalType('array', $result);

                    foreach ($result as $database) {
                        $this->assertInstanceOf(MysqlDatabase::class, $database);
                        $this->assertSame('forge', $database->name());
                    }
                }
            ],
        ];
    }

    public function getDatabasesDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/mysql/1', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['database' => $this->response()])->toResponse()
                        );
                }),
                'databaseId' => 1,
                'assertion' => function ($database) {
                    $this->assertInstanceOf(MysqlDatabase::class, $database);
                    $this->assertSame('forge', $database->name());
                }
            ],
        ];
    }

    public function deleteDatabaseDataProvider(): array
    {
        return [
            [
                'database' => new MysqlDatabase(
                    Api::fakeServer(function ($http) {
                        $http->shouldReceive('request')
                            ->with('DELETE', 'servers/1/mysql/1')
                            ->andReturn(FakeResponse::fake()->toResponse());
                    }),
                    $this->response()
                ),
                'expectedResult' => true,
            ],
        ];
    }
}
