<?php

namespace Laravel\Tests\Forge;

use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResourceCommand;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use PHPUnit\Framework\TestCase;

class ResourcesTest extends TestCase
{
    public function testResourceCommandShouldThrowExceptionIfResourceDataIsMissing()
    {
        $server = Api::fakeServer(function ($http) {
            $http->shouldReceive('request')->andReturn(
                FakeResponse::fake()->withJson([])->toResponse()
            );
        });

        $command = new FakeResourceCommand();

        $this->expectException(\InvalidArgumentException::class);
        $command->from($server);
    }

    public function testResourceCommandShouldReturnCorrectListIfResourceDataContainsEmptyArray()
    {
        $server = Api::fakeServer(function ($http) {
            $http->shouldReceive('request')->andReturn(
                FakeResponse::fake()->withJson(['bar' => []])->toResponse()
            );
        });

        $command = new FakeResourceCommand();
        $result = $command->from($server);

        $this->assertIsArray($result);
        $this->assertSame(0, count($result));
    }
}
