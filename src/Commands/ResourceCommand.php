<?php

namespace Laravel\Forge\Commands;

use Laravel\Forge\Contracts\ResourceContract;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

abstract class ResourceCommand extends ApiCommand
{
    /**
     * Resource ID.
     */
    protected $resourceId;

    /**
     * Resource path.
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Resource class name.
     *
     * @return string
     */
    abstract public function resourceClass();

    /**
     * Determines if current command is list command.
     *
     * @return bool
     */
    protected function isListCommand(): bool
    {
        return method_exists($this, 'listResponseItemsKey');
    }

    /**
     * Command name.
     *
     * @return string
     */
    public function command()
    {
        return $this->resourcePath();
    }

    /**
     * HTTP request method.
     *
     * @return string
     */
    public function requestMethod()
    {
        if ($this->isListCommand()) {
            return 'GET';
        }

        return is_null($this->getResourceId()) ? 'POST' : 'GET';
    }

    /**
     * HTTP request URL.
     *
     * @param \Laravel\Forge\Contracts\ResourceContract $resource
     *
     * @return string
     */
    public function requestUrl(ResourceContract $resource)
    {
        $resourcePath = $this->resourcePath();

        if (!is_null($this->getResourceId())) {
            $resourcePath .= '/'.$this->getResourceId();
        }

        return $resource->apiUrl($resourcePath);
    }

    /**
     * Set resource ID.
     *
     * @param int|string $resourceId
     *
     * @return static
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resource ID.
     *
     * @return int|string|null
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     *
     * @return \Laravel\Forge\Contracts\ResourceContract|array|bool|string
     */
    public function handleResponse(ResponseInterface $response, ResourceContract $owner)
    {
        if ($this->isListCommand()) {
            return $this->handleListCommandResponse($response, $owner);
        }

        $className = $this->resourceClass();

        return $className::createFromResponse($response, $owner->getApi(), $owner);
    }

    /**
     * List response handler.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function handleListCommandResponse(ResponseInterface $response, ResourceContract $owner)
    {
        $itemsKey = $this->listResponseItemsKey();

        $json = json_decode((string) $response->getBody(), true);

        if (empty($json[$itemsKey])) {
            throw new InvalidArgumentException('Given response is not a '.$this->resourcePath().' response.');
        }

        $items = [];
        $className = $this->resourceClass();

        foreach ($json[$itemsKey] as $item) {
            $items[] = new $className($owner->getApi(), $item, $owner);
        }

        return $items;
    }
}
