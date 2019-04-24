<?php

namespace Laravel\Tests\Forge;

use Laravel\Forge\Users\User;
use Laravel\Forge\Users\UsersService;
use Laravel\Tests\Forge\Helpers\Api;
use Laravel\Tests\Forge\Helpers\FakeResponse;
use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    public function testGetAuthenticatedUser()
    {
        $forge = Api::fakeForge(function ($http) {
            $http->shouldReceive('request')
                ->with('GET', 'user', ['json' => []])
                ->andReturn(
                    FakeResponse::fake()->withJson(['user' => $this->response()])->toResponse()
                );
        });

        /** @var User $user */
        $user = (new UsersService())->get()->from($forge);
        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->getId());
        $this->assertSame('Mohamed Said', $user->getName());
        $this->assertSame('mail@gmail.com', $user->getEmail());
    }

    public function response()
    {
        return [
            "id" => 1,
            "name" => "Mohamed Said",
            "email" => "mail@gmail.com",
            "card_last_four" => "1881",
            "connected_to_github" => true,
            "connected_to_gitlab" => true,
            "connected_to_bitbucket" => false,
            "connected_to_bitbucket_two" => true,
            "connected_to_digitalocean" => true,
            "connected_to_linode" => true,
            "connected_to_vultr" => true,
            "connected_to_aws" => true,
            "ready_for_billing" => true,
            "stripe_is_active" => 1,
            "stripe_plan" => "yearly-basic-199-trial",
            "subscribed" => 1,
            "can_create_servers" => true,
            "2fa_enabled" => false
        ];
    }
}
