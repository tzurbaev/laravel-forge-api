<?php

namespace Laravel\Forge\Servers;

use Laravel\Forge\ApiProvider;
use Laravel\Forge\ServerProviders\DigitalOcean;

class ServersFactory
{
    /**
     * DigitalOcean identificator.
     */
    const DIGITALOCEAN = 'ocean2';

    /**
     * Linode identificator.
     */
    const LINODE = 'linode';

    /**
     * AWS identificator.
     */
    const AWS = 'aws';

    /**
     * Custom VPS identificator.
     */
    const CUSTOM = 'custom';

    /**
     * API Provider.
     *
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

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
     * Create new DigitalOcean droplet.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function droplet(string $name)
    {
        return (new ServerBuilder($this->api, static::DIGITALOCEAN))->identifiedAs($name);
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
        return (new ServerBuilder($this->api, static::LINODE))->identifiedAs($name);
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
        return (new ServerBuilder($this->api, static::AWS))->identifiedAs($name);
    }

    /**
     * Creates new custom server.
     *
     * @param string $name
     *
     * @return \Laravel\Forge\Servers\ServerBuilder
     */
    public function custom()
    {
        return (new ServerBuilder($this->api, static::CUSTOM))->identifiedAs($name);
    }
}
