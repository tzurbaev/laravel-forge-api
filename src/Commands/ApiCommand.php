<?php

namespace Laravel\Forge\Commands;

use Laravel\Forge\Contracts\ResourceContract;
use InvalidArgumentException;

abstract class ApiCommand
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
    public function requestMethod()
    {
        return 'POST';
    }

    /**
     * HTTP request URL.
     *
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     *
     * @return string
     */
    public function requestUrl(ResourceContract $owner)
    {
        return $owner->apiUrl();
    }

    /**
     * HTTP request options.
     *
     * @return array
     */
    public function requestOptions()
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
     * Execute command on single or multiple resources.
     *
     * @param array|\Laravel\Forge\Contracts\ResourceContract $resource
     *
     * @throws \InvalidArgumentException
     *
     * @return bool|array
     */
    public function on($resource)
    {
        if (!$this->runnable()) {
            throw new InvalidArgumentException('Command execution is restricted.');
        }

        if (is_array($resource)) {
            return $this->executeOnMulitpleResources($resource);
        }

        return $this->executeOn($resource);
    }

    /**
     * Alias for "on" command.
     *
     * @param array|\Laravel\Forge\Contracts\ResourceContract $resource
     *
     * @throws \InvalidArgumentException
     *
     * @see \Laravel\Forge\Services\Commands\AbstractServiceCommand::on
     *
     * @return bool|array
     */
    public function from($resource)
    {
        return $this->on($resource);
    }

    /**
     * Execute current command on given resource.
     *
     * @param \Laravel\Forge\Contracts\ResourceContract $resource
     *
     * @return bool|mixed
     */
    protected function executeOn(ResourceContract $resource)
    {
        $response = $this->execute($resource);

        if (method_exists($this, 'handleResponse')) {
            return $this->handleResponse($response, $resource);
        }

        return true;
    }

    /**
     * Execute current command on multiple resources.
     *
     * @param array $resources
     *
     * @return array
     */
    protected function executeOnMulitpleResources(array $resources): array
    {
        $results = [];

        foreach ($resources as $resource) {
            $results[$resource->name()] = $this->executeOn($resource);
        }

        return $results;
    }

    /**
     * Execute current command.
     *
     * @param \Laravel\Forge\Contracts\ResourceContract $resource
     */
    protected function execute(ResourceContract $resource)
    {
        return $resource->getHttpClient()->request(
            $this->requestMethod(),
            $this->requestUrl($resource),
            $this->requestOptions()
        );
    }
}
