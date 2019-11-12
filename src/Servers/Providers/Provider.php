<?php

namespace Laravel\Forge\Servers\Providers;

use Laravel\Forge\Server;
use InvalidArgumentException;
use Laravel\Forge\ApiProvider;

abstract class Provider
{
    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * @var array
     */
    protected $payload = [
        'database_type' => '',
    ];

    /**
     * Create new server provider instance.
     *
     * @param \Laravel\Forge\ApiProvider $api
     */
    public function __construct(ApiProvider $api)
    {
        $this->api = $api;
        $this->initProvider();
    }

    /**
     * Initializes server provider.
     */
    protected function initProvider()
    {
        $this->payload['provider'] = $this->provider();
    }

    /**
     * Server provider name.
     *
     * @return string
     */
    public function provider()
    {
        return 'abstract';
    }

    /**
     * Server provider regions list.
     *
     * @return array
     */
    public function regions()
    {
        return [];
    }

    /**
     * Server provider server sizes.
     *
     * @return array
     */
    public function sizes()
    {
        return [];
    }

    /**
     * Available PHP versions.
     *
     * @return array
     */
    public function phpVersions()
    {
        return [56, 70, 71, 72, 73];
    }

    /**
     * Validates payload before sending to Forge API.
     *
     * @return bool|array
     */
    public function validate()
    {
        return true;
    }

    /**
     * Determines if given region is available at current provider.
     *
     * @param string $region
     *
     * @return bool
     */
    public function regionAvailable(string $region)
    {
        return $this->resourceAvailable($this->regions(), $region);
    }

    /**
     * Determines if given resource exists in resources list.
     *
     * @param array $resources
     * @param mixed $resource
     *
     * @return bool
     */
    protected function resourceAvailable(array $resources, $resource)
    {
        return isset($resources[$resource]);
    }

    /**
     * Determines if given key exists in current payload.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasPayload(string $key): bool
    {
        return !empty($this->payload[$key]);
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
     * Set server size ID.
     *
     * @param int|string $sizeId
     *
     * @return static
     */
    public function withSizeId($sizeId)
    {
        if (!is_numeric(ltrim($sizeId, 0))) {
            throw new InvalidArgumentException('Given server size ID is not valid');
        }

        $this->payload['size'] = (int) $sizeId;

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
        if (!$this->regionAvailable($region)) {
            throw new InvalidArgumentException('Given region is not supported by '.$this->provider().' provider.');
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
        $phpVersion = intval(str_replace(['php', '.'], '', $version));

        if (!in_array($phpVersion, $this->phpVersions())) {
            throw new InvalidArgumentException('PHP version "php'.$phpVersion.'" is not supported.');
        }

        $this->payload['php_version'] = 'php'.$phpVersion;

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
        $this->payload['mariadb'] = 1;
        $this->payload['database'] = $database;
        $this->payload['database_type'] = 'mariadb';

        return $this;
    }

    /**
     * Indicates that server should be provisioned with MySQL.
     * If you need to create MySQL 8 instead of 5.7, pass 8
     * as second argument.
     *
     * @param string $database = 'forge'
     * @param int    $version  = 5
     *
     * @return static
     */
    public function withMysql(string $database = 'forge', int $version = 5)
    {
        $this->payload['mariadb'] = 0;
        $this->payload['database'] = $database;
        $this->payload['database_type'] = 'mysql'.($version === 8 ? '8' : '');

        return $this;
    }

    /**
     * Indicates that server should be provisioned with PostrgreSQL.
     *
     * @param string $database = 'forge'
     *
     * @return static
     */
    public function withPostgres(string $database = 'forge')
    {
        $this->payload['mariadb'] = 0;
        $this->payload['database'] = $database;
        $this->payload['database_type'] = 'postgres';

        return $this;
    }

    /**
     * Indicates that server should be provisioned as load balancer.
     *
     * @param bool $install = true
     *
     * @deprecated since 1.3.1
     * @see Provider::asNodeBalancer()
     *
     * @return static
     */
    public function asLoadBalancer(bool $install = true)
    {
        return $this->asNodeBalancer($install);
    }

    /**
     * Indicates that server should be provisioned as load balancer.
     *
     * @param bool $install = true
     *
     * @return static
     */
    public function asNodeBalancer(bool $install = true)
    {
        return $this->togglePayload('node_balancer', $install);
    }

    /**
     * Servers ID that the new server should be connected to.
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
     * Public IP address.
     *
     * @param string $ip
     *
     * @return static
     */
    public function usingPublicIp(string $ip)
    {
        $this->payload['ip_address'] = $ip;

        return $this;
    }

    /**
     * Private IP address.
     *
     * @param string $ip
     *
     * @return static
     */
    public function usingPrivateIp(string $ip)
    {
        $this->payload['private_ip_address'] = $ip;

        return $this;
    }

    /**
     * Set recipe that should be run after provisioning.
     *
     * @param int $id
     *
     * @return static
     */
    public function withRecipe(int $id)
    {
        $this->payload['recipe_id'] = $id;

        return $this;
    }

    /**
     * Create new server.
     *
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \InvalidArgumentException
     *
     * @return \Laravel\Forge\Server
     */
    public function save()
    {
        $validationResult = $this->validate();

        if ($validationResult !== true) {
            throw new InvalidArgumentException(
                'Some required parameters are missing: '.implode(', ', $validationResult)
            );
        }

        $response = $this->api->getClient()->request('POST', 'servers', [
            'json' => $this->sortPayload(),
        ]);

        return Server::createFromResponse($response, $this->api);
    }

    /**
     * Sort payload data by key name.
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
     * Toggle boolean payload key.
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
}
