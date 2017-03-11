<?php

namespace Laravel\Forge;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Server
{
    protected $api;
    protected $data;

    public function __construct(ApiProvider $api = null, array $data = [])
    {
        $this->api = $api;
        $this->data = $data;
    }

    public static function createFromResponse(ResponseInterface $response)
    {
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['server'])) {
            throw new InvalidArgumentException('Given response is not a server response.');
        }

        return new static(null, $json['server']);
    }

    public function setApi(ApiProvider $api)
    {
        $this->api = $api;

        return $this;
    }

    protected function serverData(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    public function name()
    {
        return $this->serverData('name');
    }

    public function size()
    {
        return $this->serverData('size');
    }

    public function phpVersion()
    {
        return $this->serverData('php_version');
    }

    public function isReady()
    {
        return intval($this->serverData('is_ready')) === 1;
    }
}
