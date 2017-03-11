<?php

namespace Laravel\Forge;

use Iterator;
use ArrayAccess;
use Laravel\Forge\Traits\LazyIterator;
use Laravel\Forge\Servers\ServersFactory;
use Laravel\Forge\Traits\LazyArrayAccess;
use Laravel\Forge\Traits\AbstractCollection;

class ForgeServers implements ArrayAccess, Iterator
{
    use AbstractCollection, LazyIterator, LazyArrayAccess;

    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

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

        if (empty($data['servers'])) {
            return $this->items = [];
        }

        $servers = [];

        foreach ($data['servers'] as $server) {
            $servers[$server['name']] = $server;
        }

        return $this->items = $servers;
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
}
