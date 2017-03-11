<?php

namespace Laravel\Forge\Servers;

use Laravel\Forge\Server;
use Laravel\Forge\Regions;
use InvalidArgumentException;
use Laravel\Forge\ApiProvider;

class ServerBuilder
{
    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * @var array
     */
    protected $payload = [];

    /**
     * @var string
     */
    protected $provider;

    /**
     * Create new instance.
     *
     * @param \Laravel\Forge\ApiProvider $api
     */
    public function __construct(ApiProvider $api, string $provider)
    {
        $this->api = $api;
        $this->payload['provider'] = $provider;
    }

    /**
     * Set credential ID to create server with.
     *
     * @param int $credentialId
     *
     * @return static
     */
    public function usingCredential(int $credentialId)
    {
        $this->payload['credential_id'] = $credentialId;

        return $this;
    }

    /**
     * Set new server name.
     *
     * @param string $name
     *
     * @return static
     */
    public function identifiedAs(string $name)
    {
        $this->payload['name'] = $name;

        return $this;
    }

    /**
     * Set memory.
     *
     * @param int|string $memory
     *
     * @return static
     */
    public function withMemoryOf($memory)
    {
        $this->payload['size'] = $this->validMemoryValue($memory);

        return $this;
    }

    /**
     * Set server region.
     *
     * @param string $region
     *
     * @return static
     */
    public function at(string $region)
    {
        if (!Regions::available($region, $this->payload['provider'])) {
            throw new InvalidArgumentException(
                'Given region "'.$region.'" is not availble for "'.$this->provider.'" provider.'
            );
        }

        $this->payload['region'] = $region;

        return $this;
    }

    /**
     * Set PHP version.
     *
     * @param int|string $version
     *
     * @return static
     */
    public function runningPhp($version)
    {
        $version = 'php'.intval(str_replace(['php', '.'], '', $version));

        $this->payload['php_version'] = $version;

        return $this;
    }

    /**
     * Indicates that server should be provisioned with MariaDB instead of MySQL.
     *
     * @param string $database = 'forge'
     *
     * @return static
     */
    public function withMariaDb(string $database = 'forge')
    {
        $this->payload['maria'] = 1;
        $this->payload['database'] = $database;

        return $this;
    }

    /**
     * Indicates that server should be provisioned with MySQL.
     *
     * @param string $database = 'forge'
     *
     * @return static
     */
    public function withMysql(string $database = 'forge')
    {
        $this->payload['maria'] = 0;
        $this->payload['database'] = $database;

        return $this;
    }

    /**
     * Indicates that server should be provisioned as load balancer.
     *
     * @param bool $install = true
     *
     * @return static
     */
    public function asLoadBalancer(bool $install = true)
    {
        return $this->togglePayload('load_balancer', $install);
    }

    /**
     * Sets servers that new server should be connected to.
     *
     * @param array $servers
     *
     * @return static
     */
    public function connectedTo(array $servers)
    {
        $this->payload['network'] = $servers;

        return $this;
    }

    /**
     * Sends create new server request.
     *
     * @throws \GuzzleHttp\Exception\RequestException
     *
     * @return \Laravel\Forge\Server
     */
    public function send()
    {
        $response = $this->api->getClient()->request('POST', '/api/v1/servers', [
            'form_params' => $this->sortPayload(),
        ]);

        return Server::createFromResponse($response)->setApi($this->api);
    }

    /**
     * Sorts payload data by key name.
     *
     * @return array
     */
    protected function sortPayload(): array
    {
        $payload = $this->payload;

        ksort($payload);

        return $payload;
    }

    /**
     * Toggles boolean payload key.
     *
     * @param string $key
     * @param bool   $install
     *
     * @return static
     */
    protected function togglePayload(string $key, bool $install)
    {
        if ($install === false && isset($this->payload[$key])) {
            unset($this->payload[$key]);
        } elseif ($install === true) {
            $this->payload[$key] = 1;
        }

        return $this;
    }

    /**
     * Validates memory value.
     *
     * @param string|int $memory
     *
     * @return string
     */
    protected function validMemoryValue($memory)
    {
        $value = intval(str_replace(['mb', 'gb'], '', strtolower($memory)));

        if ($value === 0) {
            throw new InvalidArgumentException('Given size is invalid.');
        }

        if ($value >= 512) {
            return $value.'MB';
        }

        return $value.'GB';
    }
}
