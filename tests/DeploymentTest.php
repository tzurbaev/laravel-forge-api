<?php

namespace Laravel\Tests\Forge;

use Closure;
use InvalidArgumentException;
use Laravel\Forge\Sites\Site;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Sites\DeploymentManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class DeploymentTest extends TestCase
{
    /**
     * @dataProvider enableQuickDeploymentDataProvider
     */
    public function testEnableQuickDeployment(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->enable($site);

        $assertion($result);
    }

    /**
     * @dataProvider disableQuickDeploymentDataProvider
     */
    public function testDisableQuickDeployment(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->disable($site);

        $assertion($result);
    }

    /**
     * @dataProvider getDeploymentScriptDataProvider
     */
    public function testGetDeploymentScript(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->getScript($site);

        $assertion($result);
    }

    /**
     * @dataProvider updateDeploymentScriptDataProvider
     */
    public function testUpdateDeploymentScript(Site $site, string $script, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->updateScript($site, $script);

        $assertion($result);
    }

    /**
     * @dataProvider deployDataProvider
     */
    public function testDeploy(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->deploy($site);

        $assertion($result);
    }

    /**
     * @dataProvider resetDeployStatusDataProvider
     */
    public function testResetDeployStatus(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->reset($site);

        $assertion($result);
    }

    /**
     * @dataProvider getDeploymentLogDataProvider
     */
    public function testGetDeploymentLog(Site $site, Closure $assertion)
    {
        $deployment = new DeploymentManager();

        $result = $deployment->log($site);

        $assertion($result);
    }

    public function enableQuickDeploymentDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/deployment', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertTrue($result);
                }
            ],
        ];
    }

    public function disableQuickDeploymentDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1/deployment', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertTrue($result);
                }
            ],
        ];
    }

    public function getDeploymentScriptDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/deployment/script', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withBody('git pull origin master')
                                ->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertSame('git pull origin master', $result);
                }
            ],
        ];
    }

    public function updateDeploymentScriptDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('PUT', 'servers/1/sites/1/deployment/script', [
                            'form_params' => [
                                'content' => "cd /home/forge/default\ngit pull origin master",
                            ]
                        ])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withBody("cd /home/forge/default\ngit pull origin master")
                                ->toResponse()
                        );
                }),
                'script' => "cd /home/forge/default\ngit pull origin master",
                'assertion' => function ($result) {
                    $this->assertSame("cd /home/forge/default\ngit pull origin master", $result);
                }
            ],
        ];
    }

    public function deployDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/deployment/deploy', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->asserttrue($result);
                }
            ],
        ];
    }

    public function resetDeployStatusDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/deployment/reset', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->asserttrue($result);
                }
            ],
        ];
    }

    public function getDeploymentLogDataProvider(): array
    {
        $log = implode("\n", [
            'Fri Mar 10 09:32:01 UTC 2017',
            'Application is now in maintenance mode.',
            'From github.com:username/repository',
            ' * branch            master     -> FETCH_HEAD',
            '   cf55ae5..9de84a5  master     -> origin/master',
            'Updating cf55ae5..9de84a5',
            'Fast-forward',
        ]);

        return [
            [
                'site' => Api::fakeSite(function ($http) use ($log) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/deployment/log', ['form_params' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withBody($log)
                                ->toResponse()
                        );
                }),
                'assertion' => function ($result) use ($log) {
                    $this->assertSame($log, $result);
                }
            ],
        ];
    }
}
