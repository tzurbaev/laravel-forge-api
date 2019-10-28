<?php

namespace Laravel\Tests\Forge\Helpers;

use Closure;
use Mockery;
use GuzzleHttp\Client;
use Laravel\Forge\Forge;
use Laravel\Forge\Server;
use Laravel\Forge\Sites\Site;
use Laravel\Forge\ApiProvider;

class Api
{
    /**
     * Create fake API Provider.
     *
     * @param \Closure $callback = null
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public static function fake(Closure $callback = null)
    {
        $api = Mockery::mock(ApiProvider::class.'[createClient]', ['api-token']);
        $http = Mockery::mock(Client::class);

        if (!is_null($callback)) {
            $callback($http);
        }

        $api->shouldReceive('createClient')->andReturn($http);

        return $api;
    }

    /**
     * Generate server data.
     *
     * @param array $replace = []
     *
     * @return array
     */
    public static function serverData(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'credential_id' => 1,
            'name' => 'northrend',
            'size' => 1,
            'region' => 'Amsterdam 2',
            'php_version' => 'php71',
            'ip_address' => '37.139.3.148',
            'private_ip_address' => '10.129.3.252',
            'blackfire_status' => null,
            'papertail_status' => null,
            'revoked' => false,
            'created_at' => '2016-12-15 18:38:18',
            'is_ready' => true,
            'network' => [],
            'tags' => ['staging'],
            'provider' => 'ocean2',
            'provider_id' => '1668983',
            'ssh_port' => 22,
        ], $replace);
    }

    /**
     * Generate site data.
     *
     * @param array $replace = []
     *
     * @return array
     */
    public static function siteData(array $replace = []): array
    {
        return array_merge([
            'id' => 1,
            'name' => 'example.org',
            'directory' => '/public',
            'wildcards' => false,
            'status' => 'installing',
            'repository' => null,
            'repository_provider' => null,
            'repository_branch' => null,
            'repository_status' => null,
            'quick_deploy' => false,
            'project_type' => 'php',
            'app' => null,
            'app_status' => null,
            'hipchat_room' => null,
            'slack_channel' => null,
            'created_at' => '2016-12-16 16:38:08',
        ], $replace);
    }

    /**
     * Create fake site.
     *
     * @param \Closure $callback        = null
     * @param array    $replaceSiteData = []
     *
     * @return \Laravel\Forge\Sites\Site
     */
    public static function fakeSite(Closure $callback = null, array $replaceSiteData = []): Site
    {
        $server = static::fakeServer($callback);
        $site = new Site($server->getApi(), static::siteData($replaceSiteData), $server);

        return $site;
    }

    /**
     * Create fake server.
     *
     * @param \Closure $callback          = null
     * @param array    $replaceServerData = []
     *
     * @return \Laravel\Forge\Server
     */
    public static function fakeServer(Closure $callback = null, array $replaceServerData = []): Server
    {
        $api = static::fake($callback);
        $server = new Server($api, static::serverData($replaceServerData));

        return $server;
    }

    public static function fakeForge(Closure $callback = null)
    {
        $api = static::fake($callback);

        return new Forge($api);
    }

    /**
     * Create multiple fake servers.
     *
     * @param int      $number
     * @param \Closure $callback
     *
     * @return \Laravel\Forge\Server
     */
    public static function multipleFakeServers(int $number, Closure $callback = null)
    {
        $servers = [];

        for ($i = 0; $i < $number; ++$i) {
            $servers[] = static::fakeServer(
                function ($http) use ($i, $callback) {
                    if (!is_null($callback)) {
                        $callback($http, $i + 1);
                    }
                },
                [
                    'id' => $i + 1,
                    'name' => 'server'.($i + 1),
                ]
            );
        }

        return $servers;
    }
}
