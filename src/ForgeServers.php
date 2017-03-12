<?php

namespace Laravel\Forge;

use Iterator;
use ArrayAccess;
use Laravel\Forge\Traits\LazyIterator;
use Laravel\Forge\Servers\ServersFactory;
use Laravel\Forge\Traits\LazyArrayAccess;
use GuzzleHttp\Exception\RequestException;
use Laravel\Forge\Traits\AbstractCollection;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class ForgeServers implements ArrayAccess, Iterator
{
    use AbstractCollection, LazyIterator, LazyArrayAccess;

    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * server id=>name map.
     *
     * @var array
     */
    protected $serversMap = [];

    /**
     * Cache for single servers.
     *
     * @var array
     */
    protected $serversCache = [];

    /**
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
        $response = $this->api->getClient()->request('GET', '/api/v1/servers');
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
     * Generates items keys.
     */
    public function generateKeys()
    {
        $this->keys = array_keys($this->items);
    }

    /**
     * Initializes new server.
     *
     * @return \Laravel\Forge\Servers\ServersFactory
     */
    public function create()
    {
        return new ServersFactory($this->api);
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
     * Loads single server from API and saves it to memory cache.
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
            $response = $this->api->getClient()->request('GET', '/api/v1/servers/'.$serverId);
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new ServerWasNotFoundException('Server #'.$serverId.' was not found.', 404);
            }

            throw $e;
        }

        return $this->serversCache[$serverId] = Server::createFromResponse($response, $this->api);
    }
}
