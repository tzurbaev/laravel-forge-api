<?php

namespace Laravel\Tests\Forge;

use Laravel\Forge\Forge;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class CredentialsTest extends TestCase
{
    /**
     * @dataProvider getCredentialsDataProvider
     */
    public function testGetCredentials(Forge $forge, array $response)
    {
        $credentials = $forge->credentials();

        $this->assertSame($credentials, $response);
    }

    /**
     * @dataProvider getFirstCredentialDataProvider
     */
    public function testGetFirstCredential(Forge $forge, string $provider, $expectedResult)
    {
        $credential = $forge->credentialFor($provider);

        $this->assertSame($expectedResult, $credential);
    }

    public function response(array $replace): array
    {
        return array_merge([
            'id' => 1,
            'type' => 'ocean2',
            'name' => 'Personal',
        ], $replace);
    }

    public function getCredentialsDataProvider(): array
    {
        return [
            [
                'forge' => new Forge(
                    Api::fake(function ($http) {
                        $http->shouldReceive('request')
                            ->with('GET', 'credentials')
                            ->andReturn(
                                FakeResponse::fake()
                                    ->withJson([
                                        'credentials' => [
                                            $this->response(['id' => 1]),
                                            $this->response(['id' => 2]),
                                            $this->response(['id' => 3]),
                                            $this->response(['id' => 4]),
                                        ],
                                    ])
                                    ->toResponse()
                            );
                    })
                ),
                'response' => [
                    $this->response(['id' => 1]),
                    $this->response(['id' => 2]),
                    $this->response(['id' => 3]),
                    $this->response(['id' => 4]),
                ],
            ]
        ];
    }

    public function getFirstCredentialDataProvider(): array
    {
        return [
            [
                'forge' => new Forge(
                    Api::fake(function ($http) {
                        $http->shouldReceive('request')
                            ->with('GET', 'credentials')
                            ->andReturn(
                                FakeResponse::fake()
                                    ->withJson([
                                        'credentials' => [
                                            $this->response(['id' => 1, 'type' => 'ocean2']),
                                            $this->response(['id' => 2, 'type' => 'linode']),
                                            $this->response(['id' => 3, 'type' => 'ocean2']),
                                            $this->response(['id' => 4, 'type' => 'aws']),
                                        ],
                                    ])
                                    ->toResponse()
                            );
                    })
                ),
                'provider' => 'ocean2',
                'expectedResult' => 1,
            ],
            [
                'forge' => new Forge(
                    Api::fake(function ($http) {
                        $http->shouldReceive('request')
                            ->with('GET', 'credentials')
                            ->andReturn(
                                FakeResponse::fake()
                                    ->withJson([
                                        'credentials' => [
                                            $this->response(['id' => 1, 'type' => 'ocean2']),
                                            $this->response(['id' => 2, 'type' => 'linode']),
                                            $this->response(['id' => 3, 'type' => 'ocean2']),
                                        ],
                                    ])
                                    ->toResponse()
                            );
                    })
                ),
                'provider' => 'aws',
                'expectedResult' => null,
            ],
        ];
    }
}
