<?php

namespace Laravel\Forge\ServerResources\Commands;

use Laravel\Forge\Server;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Commands\ServerCommand;

abstract class ServerResourceCommand extends ServerCommand
{
    /**
     * Item ID.
     */
    protected $itemId;

    /**
     * Server resource path.
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Server resource class name.
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

        return is_null($this->itemId) ? 'POST' : 'GET';
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
        $resourcePath = $this->resourcePath();

        if (!is_null($this->getItemId())) {
            $resourcePath .= '/'.$this->getItemId();
        }

        return $server->apiUrl($resourcePath);
    }

    /**
     * Set Item ID.
     *
     * @param int|string $itemId
     *
     * @return static
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get Item ID.
     *
     * @return int|string|null
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Processes new response item.
     *
     * @param mixed $item
     *
     * @return mixed
     */
    public function processResponseItem($item)
    {
        return $item;
    }

    /**
     * Handle command response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @return \Laravel\Forge\ServerResources\ServerResource|array|bool|string
     */
    public function handleResponse(ResponseInterface $response, Server $server)
    {
        if ($this->isListCommand()) {
            return $this->handleListCommandResponse($response, $server);
        }

        $className = $this->resourceClass();

        return $this->processResponseItem(
            $className::createFromResponse($response, $server)
        );
    }

    /**
     * List response handler.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\Server               $server
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function handleListCommandResponse(ResponseInterface $response, Server $server)
    {
        $itemsKey = $this->listResponseItemsKey();

        $json = json_decode((string) $response->getBody(), true);

        if (empty($json[$itemsKey])) {
            throw new InvalidArgumentException('Given response is not a '.$this->resourcePath().' response.');
        }

        $items = [];
        $className = $this->resourceClass();

        foreach ($json[$itemsKey] as $item) {
            $items[] = $this->processResponseItem(new $className($server, $item));
        }

        return $items;
    }
}
