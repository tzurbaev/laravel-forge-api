<?php

namespace Laravel\Forge\Servers;

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Servers\Providers\Aws;
use Laravel\Forge\Servers\Providers\Custom;
use Laravel\Forge\Servers\Providers\Linode;
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
        return (new DigitalOcean($this->api))->identifiedAs($name);
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
        return (new Linode($this->api))->identifiedAs($name);
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
        return (new Aws($this->api))->identifiedAs($name);
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
}
