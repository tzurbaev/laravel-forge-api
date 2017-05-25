<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Server;
use InvalidArgumentException;
use Laravel\Forge\Sites\Site;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Sites\SitesManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use Laravel\Forge\Contracts\ApplicationContract;
use Laravel\Forge\Applications\GitApplication;
use Laravel\Forge\Applications\WordPressApplication;

class SitesTest extends TestCase
{
    /**
     * @dataProvider createSiteDataProvider
     */
    public function testCreateSite($server, Closure $factory, Closure $assertion)
    {
        $sites = new SitesManager();

        $result = $factory($sites, $server);

        $assertion($result);
    }

    /**
     * @dataProvider listSitesDataProvider
     */
    public function testListSites($server, Closure $assertion)
    {
        $sites = new SitesManager();

        $result = $sites->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getSiteDataProvider
     */
    public function testGetSite($server, int $siteId, Closure $assertion)
    {
        $sites = new SitesManager();

        $result = $sites->get($siteId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider updateSiteDataProvider
     */
    public function testUpdateSite(Site $site, array $payload, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $site->update($payload);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider installApplicationDataProvider
     */
    public function testInstallApplication(Site $site, ApplicationContract $app, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $site->install($app);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider uninstallApplicationDataProvider
     */
    public function testUninstallApplication(Site $site, ApplicationContract $app, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $site->uninstall($app);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider balanceSiteDataProvider
     */
    public function testLoadBalanceSite(Site $site, array $payload, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $site->balance($payload);

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @dataProvider deleteSiteDataProvider
     */
    public function testDeleteSite(Site $site, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $site->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function payload(array $replace = []): array
    {
        return array_merge([
            'domain' => 'example.org',
            'project_type' => 'php',
        ], $replace);
    }

    public function createSiteDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['site' => Api::siteData()])->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asPhp()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('php', $site->projectType());
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['site' => Api::siteData()])->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asLaravel()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('php', $site->projectType());
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(['project_type' => 'html']),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'site' => Api::siteData(['project_type' => 'html']),
                            ])
                            ->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asStatic()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('html', $site->projectType());
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(['project_type' => 'html']),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'site' => Api::siteData(['project_type' => 'html']),
                            ])
                            ->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asHtml()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('html', $site->projectType());
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(['project_type' => 'symfony']),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'site' => Api::siteData(['project_type' => 'symfony']),
                            ])
                            ->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asSymfony()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('symfony', $site->projectType());
                }
            ],
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites', [
                            'json' => $this->payload(['project_type' => 'symfony_dev']),
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'site' => Api::siteData(['project_type' => 'symfony_dev']),
                            ])
                            ->toResponse()
                        );
                }),
                'factory' => function (SitesManager $sites, $server) {
                    return $sites
                        ->create('example.org')
                        ->asSymfonyDev()
                        ->on($server);
                },
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('symfony_dev', $site->projectType());
                }
            ],
        ];
    }

    public function listSitesDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'sites' => [
                                        Api::siteData(['id' => 1]),
                                        Api::siteData(['id' => 2]),
                                        Api::siteData(['id' => 3]),
                                        Api::siteData(['id' => 4]),
                                    ],
                                ])
                                ->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertInternalType('array', $result);

                    foreach ($result as $site) {
                        $this->assertInstanceOf(Site::class, $site);
                        $this->assertSame('example.org', $site->domain());
                        $this->assertSame('php', $site->projectType());
                    }
                }
            ],
        ];
    }

    public function getSiteDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['site' => Api::siteData()])->toResponse()
                        );
                }),
                'siteId' => 1,
                'assertion' => function ($site) {
                    $this->assertInstanceOf(Site::class, $site);
                    $this->assertSame('example.org', $site->domain());
                    $this->assertSame('php', $site->projectType());
                }
            ],
        ];
    }

    public function updateSiteDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'servers/1/sites/1', [
                            'json' => ['directory' => '/some/path']
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'site' => Api::siteData(['directory' => '/some/path']),
                                ])
                                ->toResponse()
                        );
                }),
                'payload' => ['directory' => '/some/path'],
                'expectedResult' => true,
            ],
        ];
    }

    public function balanceSiteDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/balancing', [
                            'json' => [1 , 2]
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withBody('')
                                ->toResponse()
                        );
                }),
                'payload' => [1, 2],
                'expectedResult' => true,
            ],
        ];
    }

    public function installApplicationDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/git', [
                            'json' => [
                                'provider' => 'github',
                                'repository' => 'username/repository',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => (new GitApplication())->fromGithub('username/repository'),
                'expectedResult' => true,
            ],
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/git', [
                            'json' => [
                                'provider' => 'bitbucket',
                                'repository' => 'username/repository',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => (new GitApplication())->fromBitbucket('username/repository'),
                'expectedResult' => true,
            ],
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/git', [
                            'json' => [
                                'provider' => 'gitlab',
                                'repository' => 'username/repository',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => (new GitApplication())->fromGitlab('username/repository'),
                'expectedResult' => true,
            ],
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/git', [
                            'json' => [
                                'provider' => 'custom',
                                'repository' => 'git@example.org:username/repository.git',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => (new GitApplication())->fromGit('git@example.org:username/repository.git'),
                'expectedResult' => true,
            ],
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/wordpress', [
                            'json' => [
                                'database' => 'forge',
                                'user' => 'forge',
                            ],
                        ])
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => (new WordPressApplication())->usingDatabase('forge', 'forge'),
                'expectedResult' => true,
            ],
        ];
    }

    public function uninstallApplicationDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1/git')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => new GitApplication(),
                'expectedResult' => true,
            ],
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1/wordpress')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'app' => new WordPressApplication(),
                'expectedResult' => true,
            ],
        ];
    }

    public function deleteSiteDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
