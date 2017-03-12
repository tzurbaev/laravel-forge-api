<?php

namespace Laravel\Forge\Services\Commands;

use Laravel\Forge\Server;
use InvalidArgumentException;
use Laravel\Forge\Contracts\ServiceContract;

abstract class AbstractServiceCommand
{
    /**
     * Associated service.
     *
     * @var \Laravel\Forge\Contracts\ServiceContract
     */
    protected $service;

    /**
     * Command payload.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * @param \Laravel\Forge\Contracts\ServiceContract $service
     */
    public function __construct(ServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Command name.
     *
     * @return string
     */
    abstract public function command();

    /**
     * Determines if command can be run with current service.
     *
     * @return bool
     */
    public function runnable()
    {
        return true;
    }

    /**
     * Returns associated service.
     *
     * @return \Laravel\Forge\Contracts\ServiceContract
     */
    public function getService(): ServiceContract
    {
        return $this->service;
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
            throw new InvalidArgumentException(
                'Service "'.$this->getService()->name().'" can not be used within '.$this->command().' command.'
            );
        }

        if (is_array($server)) {
            return $this->executeOnMulitpleServers($server);
        }

        return $this->executeOn($server);
    }

    /**
     * Executes command on given server.
     *
     * @return bool
     */
    protected function executeOn(Server $server): bool
    {
        $response = $this->execute($server);

        if (method_exists($this, 'handleResponse')) {
            return $this->handleResponse($response, $server);
        }

        return true;
    }

    /**
     * Executes command on multiple servers.
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
     * Executes command.
     *
     * @param \Laravel\Forge\Server $server
     */
    protected function execute(Server $server)
    {
        return $server->getApi()->getClient()->request(
            $this->requestMethod($server),
            $server->apiUrl('/'.$this->getService()->name().'/'.$this->command()),
            $this->requestOptions($server)
        );
    }
}
