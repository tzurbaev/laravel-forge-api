<?php

namespace Laravel\Tests\Forge;

use Closure;
use InvalidArgumentException;
use Laravel\Forge\Sites\Site;
use Laravel\Forge\Workers\Worker;
use PHPUnit\Framework\TestCase;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Forge\Workers\WorkersManager;
use Laravel\Tests\Forge\Helpers\FakeResponse;

class WorkersTest extends TestCase
{
    /**
     * @dataProvider createWorkerDataProvider
     */
    public function testCreateWorker(Site $site, Closure $factory, Closure $assertion)
    {
        $workers = new WorkersManager();

        $result = $factory($workers, $site);

        $assertion($result);
    }

    /**
     * @dataProvider listWorkersDataProvider
     */
    public function testListWorkers(Site $site, Closure $assertion)
    {
        $workers = new WorkersManager();

        $result = $workers->list()->from($site);

        $assertion($result);
    }

    /**
     * @dataProvider getWorkerDataProvider
     */
    public function testGetWorker(Site $site, int $workerId, Closure $assertion)
    {
        $workers = new WorkersManager();

        $result = $workers->get($workerId)->from($site);

        $assertion($result);
    }

    /**
     * @dataProvider deleteWorkerDataProvider
     */
    public function testDeleteWorker(Worker $worker, $expectedResult, bool $exception = false)
    {
        if ($exception === true) {
            $this->expectException(InvalidArgumentException::class);
        }

        $result = $worker->delete();

        $this->assertSame($expectedResult, $result);
    }

    protected function payload(array $replace = []): array
    {
        return array_merge([
            'connection' => 'sqs',
            'timeout' => 90,
            'sleep' => 60,
            'tries' => 3,
            'daemon' => true,
        ], $replace);
    }

    protected function response(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'connection' => 'sqs',
            'command' => 'php /home/forge/default/artisan queue:work sqs --sleep=60 --daemon --quiet --timeout=90',
            'queue' => null,
            'timeout' => 90,
            'sleep' => 60,
            'tries' => 3,
            'environment' => null,
            'daemon' => 1,
            'status' => 'installing',
            'created_at' => '2016-12-17 07:15:03',
        ], $replace);
    }

    public function createWorkerDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('POST', 'servers/1/sites/1/workers', ['json' => $this->payload()])
                        ->andReturn(
                            FakeResponse::fake()->withJson(['worker' => $this->response()])->toResponse()
                        );
                }),
                'factory' => function (WorkersManager $workers, Site $site) {
                    return $workers->start('sqs')
                        ->withTimeout(90)
                        ->sleepFor(60)
                        ->maxTries(3)
                        ->asDaemon()
                        ->on($site);
                },
                'assertion' => function ($worker) {
                    $this->assertInstanceOf(Worker::class, $worker);
                    $this->assertSame('sqs', $worker->connection());
                    $this->assertSame(90, $worker->timeout());
                    $this->assertSame(3, $worker->maxTries());
                    $this->assertTrue($worker->daemon());
                }
            ],
        ];
    }

    public function listWorkersDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/workers', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'workers' => [
                                    $this->response(['id' => 2]),
                                    $this->response(['id' => 3]),
                                    $this->response(['id' => 4]),
                                    $this->response(['id' => 5]),
                                ],
                            ])
                            ->toResponse()
                        );
                }),
                'assertion' => function ($result) {
                    $this->assertInternalType('array', $result);

                    foreach ($result as $worker) {
                        $this->assertInstanceOf(Worker::class, $worker);
                        $this->assertSame('sqs', $worker->connection());
                        $this->assertSame(90, $worker->timeout());
                        $this->assertSame(3, $worker->maxTries());
                        $this->assertTrue($worker->daemon());
                    }
                }
            ],
        ];
    }

    public function getWorkerDataProvider(): array
    {
        return [
            [
                'site' => Api::fakeSite(function ($http) {
                    $http->shouldReceive('request')
                        ->with('GET', 'servers/1/sites/1/workers/2', ['json' => []])
                        ->andReturn(
                            FakeResponse::fake()->withJson([
                                'worker' => $this->response(['id' => 2]),
                            ])
                            ->toResponse()
                        );
                }),
                'workerId' => 2,
                'assertion' => function ($worker) {
                    $this->assertInstanceOf(Worker::class, $worker);
                    $this->assertSame('sqs', $worker->connection());
                    $this->assertSame(90, $worker->timeout());
                    $this->assertSame(3, $worker->maxTries());
                    $this->assertTrue($worker->daemon());
                }
            ],
        ];
    }

    public function fakeWorker(Closure $callback = null, array $replace = []): Worker
    {
        $site = Api::fakeSite($callback);

        return new Worker($site->getApi(), $this->response($replace), $site);
    }

    public function deleteWorkerDataProvider(): array
    {
        return [
            [
                'worker' => $this->fakeWorker(function ($http) {
                    $http->shouldReceive('request')
                        ->with('DELETE', 'servers/1/sites/1/workers/1')
                        ->andReturn(FakeResponse::fake()->toResponse());
                }),
                'expectedResult' => true,
            ],
        ];
    }
}
