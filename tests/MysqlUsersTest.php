<?php

namespace Laravel\Tests\Forge;

use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Services\Mysql\MysqlUser;
use Laravel\Forge\Services\Mysql\MysqlUsers;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Forge\Services\Mysql\MysqlDatabase;

class MysqlUsersTest extends TestCase
{
    /**
     * @dataProvider createUserDataProvider
     */
    public function testCreateUser($server, array $payload)
    {
        $users = new MysqlUsers();

        $user = $users->create($payload['name'], $payload['password'])
            ->withAccessTo($payload['databases'])
            ->on($server);

        $this->assertInstanceOf(MysqlUser::class, $user);
        $this->assertSame($payload['name'], $user->name());
        $this->assertSame($payload['databases'], $user->databases());
    }

    /**
     * @dataProvider listUsersDataProvider
     */
    public function testListUsers($server, Closure $assertion)
    {
        $users = new MysqlUsers();

        $result = $users->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getUserDataProvider
     */
    public function testGetUser($server, int $userId, Closure $assertion)
    {
        $users = new MysqlUsers();

        $result = $users->get($userId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider updateUserDataProvider
     */
    public function testUpdateUser(MysqlUser $user, array $payload, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $user->update($payload);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider deleteUserDataProvider
     */
    public function testDeleteUser(MysqlUser $user, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $user->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function payload(array $replace = []): array
    {
        return array_merge([
            'name' => 'forge',
            'password' => 'secret',
            'databases' => [1],
        ], $replace);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'forge',
            'status' => 'installing',
            'created_at' => '2016-12-16 16:19:01',
            'databases' => [1],
        ], $replace);
    }

    public function fakeDatabase(array $replace = []): MysqlDatabase
    {
        $server = Api::fakeServer();

        return new MysqlDatabase($server->getApi(), $this->response($replace), $server);
    }

    public function fakeUser(Closure $callback, array $replace = []): MysqlUser
    {
        $server = Api::fakeServer($callback);

        return new MysqlUser($server->getApi(), $this->response($replace), $server);
    }

    public function createUserDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/mysql-users', ['form_params' => $this->payload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['user' => $this->response()])->toResponse()
                        );
                }),
                'payload' => $this->payload(),
            ],
        ];
    }

    public function listUsersDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/mysql-users', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'users' => [
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
                    $database = $this->fakeDatabase();

                    foreach ($result as $user) {
                        $this->assertInstanceOf(MysqlUser::class, $user);
                        $this->assertSame('forge', $user->name());
                        $this->assertTrue($user->hasAccessTo(1));
                        $this->assertTrue($user->hasAccessTo($database));
                    }
                }
            ],
        ];
    }

    public function getUserDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/mysql-users/1', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['user' => $this->response()])->toResponse()
                        );
                }),
                'userId' => 1,
                'assertion' => function ($user) {
                    $this->assertInstanceOf(MysqlUser::class, $user);
                    $this->assertSame('forge', $user->name());
                    $this->assertTrue($user->hasAccessTo(1));
                    $this->assertTrue($user->hasAccessTo($this->fakeDatabase()));
                }
            ],
        ];
    }

    public function updateUserDataProvider(): array
    {
        return [
            [
                'user' => $this->fakeUser(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'servers/1/mysql-users/1', [
                            'form_params' => [
                                'databases' => [1, 2],
                            ]
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'user' => $this->response([
                                        'databases' => [1, 2],
                                    ]),
                                ])
                                ->toResponse()
                        );
                }),
                'payload' => [
                    'databases' => [1, 2],
                ],
                'expectedResult' => true,
            ],
        ];
    }

    public function deleteUserDataProvider(): array
    {
        return [
            [
                'user' => $this->fakeUser(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/mysql-users/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
