<?php

namespace Laravel\Tests\Forge;

use Closure;
use Laravel\Forge\Server;
use Laravel\Forge\Jobs\Job;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Laravel\Forge\Jobs\JobsManager;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class JobsTest extends TestCase
{
    protected $command = 'php /home/forge/default/artisan schedule:run';

    /**
     * @dataProvider createJobDataProvider
     */
    public function testCreateJob($server, array $payload, Closure $factory, bool $exception = false)
    {
        $jobs = new JobsManager();

        $job = $factory($jobs, $server);

        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $job->on($server);

        $this->assertInstanceOf(Job::class, $result);

        foreach ($payload as $key => $value) {
            $this->assertSame($value, $result[$key]);
        }
    }

    /**
     * @dataProvider listJobsDataProvider
     */
    public function testListJobs($server, Closure $assertion)
    {
        $jobs = new JobsManager();

        $result = $jobs->list()->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider getJobDataProvider
     */
    public function testGetJob($server, int $jobId, Closure $assertion)
    {
        $jobs = new JobsManager();

        $result = $jobs->get($jobId)->from($server);

        $assertion($result);
    }

    /**
     * @dataProvider deleteJobDataProvider
     */
    public function testDeleteJob(Job $job, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $job->delete();

        $this->assertSame($expectedResult, $result);
    }

    public function serverWithCreateRequestMock(array $replace = []): Server
    {
        $payload = $this->payload($replace);
        $response = $this->response($replace);

        return Api::fakeServer(function ($http) use ($payload, $response) {
            $http->shouldReceive('request')
                ->with('POST', 'servers/1/jobs', [
                    'json' => $payload,
                ])
                ->andReturn(
                    FakeResponse::fake()
                        ->withJson(['job' => $response])
                        ->toResponse()
                );
        });
    }

    public function payload(array $replace = []): array
    {
        return array_merge([
            'command' => $this->command,
            'user' => 'forge',
        ], $replace);
    }

    public function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'command' => $this->command,
            'user' => 'forge',
            'frequency' => 'nightly',
            'status' => 'installing',
            'created_at' => '2016-12-16 15:56:59',
        ], $replace);
    }

    public function fakeJob(Closure $callback, array $replace = []): Job
    {
        $server = Api::fakeServer($callback);

        return new Job($server->getApi(), $this->response($replace), $server);
    }

    public function createJobDataProvider(): array
    {
        return [
            [
                'server' => $this->serverWithCreateRequestMock(['frequency' => 'minutely']),
                'payload' => $this->payload(['frequency' => 'minutely']),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->everyMinute();
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock(['frequency' => 'hourly']),
                'payload' => $this->payload(['frequency' => 'hourly']),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->hourly();
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock(['frequency' => 'nightly']),
                'payload' => $this->payload(['frequency' => 'nightly']),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->nightly();
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock(['frequency' => 'weekly']),
                'payload' => $this->payload(['frequency' => 'weekly']),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->weekly();
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock(['frequency' => 'monthly']),
                'payload' => $this->payload(['frequency' => 'monthly']),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->monthly();
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock([
                    'frequency' => 'custom',
                    'hour' => '12',
                    'minute' => '00',
                    'day' => 15,
                    'month' => '*',
                    'weekday' => '*',
                ]),
                'payload' => $this->payload([
                    'frequency' => 'custom',
                    'hour' => '12',
                    'minute' => '00',
                    'day' => 15,
                    'month' => '*',
                    'weekday' => '*',
                ]),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->atTime('12:00')
                        ->atDay(15)
                        ->atMonth('*')
                        ->atWeekday('*');
                },
            ],
            [
                'server' => $this->serverWithCreateRequestMock([
                    'frequency' => 'custom',
                    'hour' => '12',
                    'minute' => '00',
                ]),
                'payload' => $this->payload([
                    'frequency' => 'custom',
                    'hour' => '12',
                    'minute' => '00',
                ]),
                'factory' => function (JobsManager $jobs, Server $server) {
                    return $jobs->schedule($this->command)
                        ->runningAs('forge')
                        ->atTime('12:00');
                },
                'exception' => true,
            ],
        ];
    }

    public function listJobsDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/jobs', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()
                                ->withJson([
                                    'jobs' => [
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

                    foreach ($result as $job) {
                        $this->assertInstanceOf(Job::class, $job);
                        $this->assertSame($this->command, $job->command());
                        $this->assertSame('nightly', $job->frequency());
                    }
                }
            ],
        ];
    }

    public function getJobDataProvider(): array
    {
        return [
            [
                'server' => Api::fakeServer(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/jobs/1', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['job' => $this->response()])->toResponse()
                        );
                }),
                'jobId' => 1,
                'assertion' => function ($job) {
                    $this->assertInstanceOf(Job::class, $job);
                    $this->assertSame($this->command, $job->command());
                    $this->assertSame('nightly', $job->frequency());
                }
            ],
        ];
    }

    public function deleteJobDataProvider()
    {
        return [
            [
                'daemon' => $this->fakeJob(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/jobs/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
