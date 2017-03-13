<?php

namespace Laravel\Forge;

use Iterator;
use ArrayAccess;
use Laravel\Forge\Servers\Factory;
use Laravel\Forge\Traits\LazyIterator;
use Laravel\Forge\Traits\LazyArrayAccess;
use GuzzleHttp\Exception\RequestException;
use Laravel\Forge\Traits\AbstractCollection;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class Forge implements ArrayAccess, Iterator
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
     * @param int $serverId
     *
     * @return \Laravel\Forge\Server
     */
    public function get(int $serverId)
    {
        if ($this->lazyLoadInitiated() && isset($this->serversMap[$serverId])) {
            return $this->items[$this->serversMap[$serverId]];
        } elseif (isset($this->serversCache[$serverId])) {
            return $this->serversCache[$serverId];
        }

        return $this->loadSingleServer($serverId);
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
}
