<?php

namespace Laravel\Tests\Forge;

use Closure;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Firewall\FirewallRule;
use Laravel\Forge\Firewall\FirewallManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class FirewallTest extends TestCase
{
    /**
     * @dataProvider createFirewallRuleDataProvider
     */
    public function testCreateFirewallRule($server, array $rule, Closure $assertion)
    {
        $firewall = new FirewallManager();

        $result = $firewall
            ->create($rule['name'])
            ->usingPort($rule['port'])
            ->usingIp($rule['ip_address'])
            ->on($server);

        $assertion($result);
    }

    /**
     * @dataProvider listFirewallRulesDataProvider
     */
    public function testListFirewallRules($server, Closure $assertion)
    {
        $firewall = new FirewallManager();

        $result = $firewall->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getFirewallRuleDataProvider
     */
    public function testGetFirewallRule($server, int $ruleId, Closure $assertion)
    {
        $firewall = new FirewallManager();

        $result = $firewall->get($ruleId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider deleteFirewallRuleDataProvider
     */
    public function testDeleteFirewallRule(FirewallRule $rule, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $rule->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'rule name',
            'port' => 88,
            'ip_address' => '192.168.0.1',
            'status' => 'installing',
            'created_at' => '2016-12-16 15:50:17',
        ], $replace);
    }

    public function fakeRule(Closure $callback, array $replace = []): FirewallRule
    {
        $server = Api::fakeServer($callback);

        return new FirewallRule($server->getApi(), $this->response($replace), $server);
    }

    public function createFirewallRuleDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/firewall-rules', [
                            'json' => [
                                'name' => 'rule name',
                                'ip_address' => '192.168.0.1',
                                'port' => 88,
                            ],
                        ])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['rule' => $this->response()])->toResponse()
                        );
                }),
                'rule' => [
                    'name' => 'rule name',
                    'ip_address' => '192.168.0.1',
                    'port' => 88,
                ],
                'assertion' => function ($rule) {
                    $this->assertInstanceOf(FirewallRule::class, $rule);
                    $this->assertSame('rule name', $rule->name());
                    $this->assertSame('192.168.0.1', $rule->ipAddress());
                    $this->assertSame(88, $rule->port());
                }
            ],
        ];
    }

    public function listFirewallRulesDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/firewall-rules', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'rules' => [
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

                    foreach ($result as $rule) {
                        $this->assertInstanceOf(FirewallRule::class, $rule);
                        $this->assertSame('rule name', $rule->name());
                        $this->assertSame(88, $rule->port());
                    }
                }
            ],
        ];
    }

    public function getFirewallRuleDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/firewall-rules/1', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['rule' => $this->response()])->toResponse()
                        );
                }),
                'ruleId' => 1,
                'assertion' => function ($rule) {
                    $this->assertInstanceOf(FirewallRule::class, $rule);
                    $this->assertSame('rule name', $rule->name());
                    $this->assertSame(88, $rule->port());
                }
            ],
        ];
    }

    public function deleteFirewallRuleDataProvider(): array
    {
        return [
            [
                'rule' => $this->fakeRule(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/firewall-rules/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
