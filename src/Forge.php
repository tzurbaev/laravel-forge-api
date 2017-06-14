<?php

namespace Laravel\Forge;

use Iterator;
use ArrayAccess;
use GuzzleHttp\ClientInterface;
use Laravel\Forge\Servers\Factory;
use Laravel\Forge\Traits\LazyIterator;
use Laravel\Forge\Traits\LazyArrayAccess;
use GuzzleHttp\Exception\RequestException;
use Laravel\Forge\Traits\AbstractCollection;
use Laravel\Forge\Contracts\ResourceContract;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class Forge implements ArrayAccess, Iterator, ResourceContract
{
    use AbstractCollection, LazyIterator, LazyArrayAccess;

    /**
     * API provider.
     *
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * Servers [id => name] map.
     *
     * @var array
     */
    protected $serversMap = [];

    /**
     * Single servers cache.
     *
     * @var array
     */
    protected $serversCache = [];

    /**
     * Create new Servers manager instance.
     *
     * @param \Laravel\Forge\ApiProvider $api
     */
    public function __construct(ApiProvider $api)
    {
        $this->api = $api;
    }

    /**
     * Get API provider.
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public function getApi(): ApiProvider
    {
        return $this->api;
    }

    /**
     * Get underlying API provider's HTTP client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->api->getClient();
    }

    /**
     * Resource API URL.
     *
     * @param string $path            = ''
     * @param bool   $withPropagation = true
     *
     * @return string
     */
    public function apiUrl(string $path = '', bool $withPropagation = true): string
    {
        return $path;
    }

    /**
     * Resource name.
     *
     * @return string
     */
    public function name()
    {
        return 'forge';
    }

    /**
     * @{inheritdocs}
     */
    public function lazyLoad()
    {
        $response = $this->api->getClient()->request('GET', 'servers');
        $data = json_decode((string) $response->getBody(), true);

        $this->items = [];
        $this->serversMap = [];

        if (empty($data['servers'])) {
            return $this->items;
        }

        foreach ($data['servers'] as $server) {
            $this->items[$server['name']] = new Server($this->api, $server);
            $this->serversMap[$server['id']] = $server['name'];
        }

        return $this->items;
    }

    /**
     * Generate items keys.
     */
    public function generateKeys()
    {
        $this->keys = array_keys($this->items);
    }

    /**
     * Initialize servers factory.
     *
     * @return \Laravel\Forge\Servers\Factory
     */
    public function create()
    {
        return new Factory($this->api);
    }

    /**
     * Returns single server.
     *
     * @param int  $serverId
     * @param bool $reload   (optional) indicates whether the server should be reloaded
     *
     * @return \Laravel\Forge\Server
     */
    public function get(int $serverId, bool $reload = false)
    {
        if ($reload === true) {
            return $this->loadSingleServer($serverId);
        }

        if ($this->lazyLoadInitiated() && isset($this->serversMap[$serverId])) {
            return $this->items[$this->serversMap[$serverId]];
        } elseif (isset($this->serversCache[$serverId])) {
            return $this->serversCache[$serverId];
        }

        return $this->loadSingleServer($serverId);
    }

    /**
     * Get server provider credentials.
     *
     * @return array
     */
    public function credentials(): array
    {
        $response = $this->api->getClient()->request('GET', 'credentials');
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['credentials'])) {
            return [];
        }

        return $json['credentials'];
    }

    /**
     * Get first credential for given provider.
     *
     * @param string $provider
     *
     * @return int|null
     */
    public function credentialFor(string $provider)
    {
        $credentials = $this->credentials();

        if (!count($credentials)) {
            return;
        }

        foreach ($credentials as $credential) {
            if ($credential['type'] === $provider) {
                return intval($credential['id']);
            }
        }
    }

    /**
     * Load single server from API and save it to memory cache.
     *
     * @param int $serverId
     *
     * @throws \Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException
     *
     * @return \Laravel\Forge\Server
     */
    protected function loadSingleServer(int $serverId)
    {
        try {
            $response = $this->api->getClient()->request('GET', 'servers/'.$serverId);
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new ServerWasNotFoundException('Server #'.$serverId.' was not found.', 404);
            }

            throw $e;
        }

        return $this->serversCache[$serverId] = Server::createFromResponse($response, $this->api);
    }

    /**
     * Sets an optional rate limiting function on the api provider.
     *
     * @param \Closure
     */
    public function setRateLimiter(Callable $rateLimiter)
    {
        $this->api->setRateLimiter($rateLimiter);
    }
}
