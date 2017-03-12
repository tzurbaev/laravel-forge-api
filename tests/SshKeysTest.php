<?php

namespace Laravel\Tests\Forge;

use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Forge\SshKeys\SshKey;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\SshKeys\SshKeysManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class SshKeysTest extends TestCase
{
    /**
     * @dataProvider createKeyDataProvider
     */
    public function testCreateKey($server, array $payload, Closure $assertion)
    {
        $keys = new SshKeysManager();

        $result = $keys->create($payload['name'])
            ->withContent($payload['key'])
            ->on($server);

        $assertion($result);
    }

    /**
     * @dataProvider listKeysDataProvider
     */
    public function testListKeys($server, Closure $assertion)
    {
        $keys = new SshKeysManager();

        $result = $keys->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getKeyDataProvider
     */
    public function testGetKey($server, int $keyId, Closure $assertion)
    {
        $keys = new SshKeysManager();

        $result = $keys->get($keyId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider deleteKeyDataProvider
     */
    public function testDeleteKey(SshKey $key, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $key->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function payload(array $replace = []): array
    {
        return array_merge([
            'name' => 'test-key',
            'key' => 'secret',
        ], $replace);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'test-key',
            'status' => 'installing',
            'created_at' => '2016-12-16 16:31:16',
        ], $replace);
    }

    public function createKeyDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/keys', ['form_params' => $this->payload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['key' => $this->response()])->toResponse()
                        );
                }),
                'payload' => $this->payload(),
                'assertion' => function ($key) {
                    $this->assertInstanceOf(SshKey::class, $key);
                    $this->assertSame('test-key', $key->name());
                }
            ],
        ];
    }

    public function listKeysDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/keys', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'keys' => [
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

                    foreach ($result as $key) {
                        $this->assertInstanceOf(SshKey::class, $key);
                        $this->assertSame('test-key', $key->name());
                    }
                }
            ],
        ];
    }

    public function getKeyDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/keys/1', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['key' => $this->response()])->toResponse()
                        );
                }),
                'keyId' => 1,
                'assertion' => function ($key) {
                    $this->assertInstanceOf(SshKey::class, $key);
                    $this->assertSame('test-key', $key->name());
                }
            ],
        ];
    }

    public function deleteKeyDataProvider(): array
    {
        return [
            [
                'key' => new SshKey(
                    Api::fakeServer(function ($http) {
                        $http->shouldReceive('request')
                            ->with('DELETE', 'servers/1/keys/1')
                            ->andReturn(
                                FakeResponse::fake()->withJson(['key' => $this->response()])->toResponse()
                            );
                    }),
                    $this->response()
                ),
                'expectedResult' => true,
            ],
        ];
    }
}
