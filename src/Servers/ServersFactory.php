<?php

namespace Laravel\Forge\Servers;

use Laravel\Forge\Server;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Servers\Providers\Aws;
use Laravel\Forge\Servers\Providers\Custom;
use Laravel\Forge\Servers\Providers\Linode;
use Laravel\Forge\Servers\Providers\Provider;
use Laravel\Forge\Servers\Providers\DigitalOcean;

class ServersFactory
{
    /**
     * API Provider.
     *
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * Default credentials for different providers.
     *
     * @var array
     */
    protected static $defaultCredentials = [];

    /**
     * Create new instance.
     *
     * @param \Laravel\Forge\ApiProvider
     */
    public function __construct(ApiProvider $api)
    {
        $this->api = $api;
    }

    /**
     * Set default credential ID for given provider.
     *
     * @param string $provider
     * @param int    $credentialId
     */
    public static function setDefaultCredential(string $provider, int $credentialId)
    {
        static::$defaultCredentials[$provider] = $credentialId;
    }

    /**
     * Remove default credential for given provider or for all providers.
     *
     * @param string $provider = null
     */
    public function resetDefaultCredential(string $provider = null)
    {
        if (is_null($provider)) {
            static::$defaultCredentials = [];

            return;
        }

        unset(static::$defaultCredentials[$provider]);
    }

    /**
     * Create new DigitalOcean droplet.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function droplet(string $name)
    {
        return $this->applyDefaultCredential(
            (new DigitalOcean($this->api))->identifiedAs($name)
        );
    }

    /**
     * Creates new Linode server.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function linode(string $name)
    {
        return $this->applyDefaultCredential(
            (new Linode($this->api))->identifiedAs($name)
        );
    }

    /**
     * Creates new AWS server.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function aws(string $name)
    {
        return $this->applyDefaultCredential(
            (new Aws($this->api))->identifiedAs($name)
        );
    }

    /**
     * Creates new custom server.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function custom(string $name)
    {
        return (new Custom($this->api))->identifiedAs($name);
    }

    /**
     * Create new server from raw payload data.
     *
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\RequestException
     *
     * @return \Laravel\Forge\Server
     */
    public function server(array $payload)
    {
        ksort($payload);

        $response = $this->api->getClient()->request('POST', 'servers', [
            'form_params' => $payload,
        ]);

        return Server::createFromResponse($response, $this->api);
    }

    /**
     * Apply default credential ID for given provider (if exists).
     *
     * @param \Laravel\Forge\Servers\Providers\Provider $provider
     *
     * @return \Laravel\Forge\Servers\Providers\Provider
     */
    protected function applyDefaultCredential(Provider $provider): Provider
    {
        if (!empty(static::$defaultCredentials[$provider->provider()])) {
            $provider->usingCredential(
                static::$defaultCredentials[$provider->provider()]
            );
        }

        return $provider;
    }
}
