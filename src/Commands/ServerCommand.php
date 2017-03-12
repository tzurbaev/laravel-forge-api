<?php

namespace Laravel\Forge\Commands;

use Laravel\Forge\Server;
use InvalidArgumentException;

abstract class ServerCommand
{
    /**
     * Command payload.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * Command name.
     *
     * @return string
     */
    abstract public function command();

    /**
     * Command description.
     *
     * @return string
     */
    public function description()
    {
        return '';
    }

    /**
     * Determines if command can run.
     *
     * @return bool
     */
    public function runnable()
    {
        return true;
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod(Server $server)
    {
        return 'POST';
    }

    /**
     * HTTP request URL.
     *
     * @param \Laravel\Forge\Server
     *
     * @return string
     */
    public function requestUrl(Server $server)
    {
        return $server->apiUrl();
    }

    /**
     * HTTP request options.
     *
     * @return array
     */
    public function requestOptions(Server $server)
    {
        return [
            'form_params' => $this->payload,
        ];
    }

    /**
     * Set command payload.
     *
     * @param array $payload
     *
     * @return static
     */
    public function withPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Set payload data.
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return static
     */
    public function attachPayload($key, $value)
    {
        if (is_null($this->payload)) {
            $this->payload = [];
        }

        $this->payload[$key] = $value;

        return $this;
    }

    /**
     * Return payload data.
     *
     * @param string|int $key
     * @param mixed      $default = null
     *
     * @return mixed|null
     */
    public function getPayloadData($key, $default = null)
    {
        if (is_null($this->payload)) {
            return;
        }

        return $this->payload[$key] ?? $default;
    }

    /**
     * Determines if payload has requried keys.
     *
     * @param string|int|array $keys
     *
     * @return bool
     */
    public function hasPayloadData($keys): bool
    {
        if (is_null($this->payload)) {
            return false;
        }

        if (!is_array($keys)) {
            $keys = [$keys];
        }

        foreach ($keys as $key) {
            if (!isset($this->payload[$key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute command on single or multiple servers.
     *
     * @param array|\Laravel\Forge\Server $server
     *
     * @throws \InvalidArgumentException
     *
     * @return bool|array
     */
    public function on($server)
    {
        if (!$this->runnable()) {
            throw new InvalidArgumentException('Command execution is restricted.');
        }

        if (is_array($server)) {
            return $this->executeOnMulitpleServers($server);
        }

        return $this->executeOn($server);
    }

    /**
     * Alias for "on" command.
     *
     * @param array|\Laravel\Forge\Server $server
     *
     * @throws \InvalidArgumentException
     *
     * @see \Laravel\Forge\Services\Commands\AbstractServiceCommand::on
     *
     * @return bool|array
     */
    public function from($server)
    {
        return $this->on($server);
    }

    /**
     * Execute current command on given server.
     *
     * @return bool|mixed
     */
    protected function executeOn(Server $server)
    {
        $response = $this->execute($server);

        if (method_exists($this, 'handleResponse')) {
            return $this->handleResponse($response, $server);
        }

        return true;
    }

    /**
     * Execute current command on multiple servers.
     *
     * @param array $servers
     *
     * @return array
     */
    protected function executeOnMulitpleServers(array $servers): array
    {
        $results = [];

        foreach ($servers as $server) {
            $results[$server->name()] = $this->executeOn($server);
        }

        return $results;
    }

    /**
     * Execute current command.
     *
     * @param \Laravel\Forge\Server $server
     */
    protected function execute(Server $server)
    {
        return $server->getApi()->getClient()->request(
            $this->requestMethod($server),
            $this->requestUrl($server),
            $this->requestOptions($server)
        );
    }
}
