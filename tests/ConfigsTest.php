<?php

namespace Laravel\Tests\Forge;

use InvalidArgumentException;
use Laravel\Forge\Sites\Site;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Configs\ConfigsManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class ConfigurationsTest extends TestCase
{
    /**
     * @dataProvider getNginxConfigurationDataProvider
     */
    public function testGetNginxConfiguration(Site $site, string $expectedResult, bool $exception = false)
    {
        $configs = new ConfigsManager();

        if ($exception === true) {
            $this->exepectException(InvalidArgumentException::class);
        }

        $result = $configs->get('nginx')->from($site);
        $this->assertSame($result, $expectedResult);
    }

    /**
     * @dataProvider updateNginxConfigurationDataProvider
     */
    public function testUpdateNginxConfiguration(Site $site, string $config, $expectedResult, bool $exception = false)
    {
        $configs = new ConfigsManager();

        if ($exception === true) {
            $this->exepectException(InvalidArgumentException::class);
        }

        $result = $configs->update('nginx', $config)->on($site);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider getEnvFileDataProvider
     */
    public function testGetEnvFile(Site $site, string $expectedResult, bool $exception = false)
    {
        $configs = new ConfigsManager();

        if ($exception === true) {
            $this->exepectException(InvalidArgumentException::class);
        }

        $result = $configs->get('env')->from($site);
        $this->assertSame($result, $expectedResult);
    }

    /**
     * @dataProvider updateEnvFileDataProvider
     */
    public function testUpdateEnvFile(Site $site, string $env, $expectedResult, bool $exception = false)
    {
        $configs = new ConfigsManager();

        if ($exception === true) {
            $this->exepectException(InvalidArgumentException::class);
        }

        $result = $configs->update('env', $env)->on($site);
        $this->assertSame($result, $expectedResult);
    }

    public function nginx(): string
    {
        return file_get_contents(__DIR__.'/files/nginx.conf');
    }

    public function env()
    {
        return file_get_contents(__DIR__.'/files/env');
    }

    public function getNginxConfigurationDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/nginx', ['json' => []])
                        ->andReturn(FakeResponse::fake()->withBody($this->nginx())->toResponse());
                }),
                'expectedResult' => $this->nginx(),
            ],
        ];
    }

    public function updateNginxConfigurationDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'servers/1/sites/1/nginx', [
                            'json' => ['content' => $this->nginx()],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'config' => $this->nginx(),
                'expectedResult' => true,
            ],
        ];
    }

    public function getEnvFileDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/env', ['json' => []])
                        ->andReturn(FakeResponse::fake()->withBody($this->env())->toResponse());
                }),
                'expectedResult' => $this->env(),
            ],
        ];
    }

    public function updateEnvFileDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'servers/1/sites/1/env', [
                            'json' => ['content' => $this->env()],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'config' => $this->env(),
                'expectedResult' => true,
            ],
        ];
    }
}
