<?php

namespace Laravel\Forge;

use ArrayAccess;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Traits\ArrayAccessTrait;
use Laravel\Forge\Exceptions\Servers\PublicKeyWasNotFound;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class Server implements ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * API provider.
     *
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * Server data.
     *
     * @var array
     */
    protected $data;

    /**
     * Create new server instance.
     *
     * @param \Laravel\Forge\ApiProvider $api  = null
     * @param array                      $data = []
     */
    public function __construct(ApiProvider $api = null, array $data = [])
    {
        $this->api = $api;
        $this->data = $data;
    }

    /**
     * Create new server instance from HTTP response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\ApiProvider          $api      = null
     *
     * @throws \Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException
     *
     * @return static
     */
    public static function createFromResponse(ResponseInterface $response, ApiProvider $api = null)
    {
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['server'])) {
            throw new ServerWasNotFoundException('Given response is not a server response.');
        }

        return new static($api, $json['server']);
    }

    /**
     * Server data.
     *
     * @param string $key     = null
     * @param mixed  $default = null
     *
     * @return mixed|array|null
     */
    protected function serverData(string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? $default;
    }

    /**
     * API provider.
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public function getApi(): ApiProvider
    {
        return $this->api;
    }

    /**
     * Server ID.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->serverData('id'));
    }

    /**
     * Server name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->serverData('name');
    }

    /**
     * Human readable server size.
     *
     * @return string|null
     */
    public function size()
    {
        return $this->serverData('size');
    }

    /**
     * Server's PHP version.
     *
     * @return string|null
     */
    public function phpVersion()
    {
        return $this->serverData('php_version');
    }

    /**
     * Determines if server was provisioned and ready to use.
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return intval($this->serverData('is_ready')) === 1;
    }

    /**
     * Server's API URL.
     *
     * @param string $path = ''
     *
     * @return string
     */
    public function apiUrl(string $path = ''): string
    {
        $path = ($path ? '/'.ltrim($path, '/') : '');

        return 'servers/'.$this->id().$path;
    }

    /**
     * Update server data.
     *
     * @param array $payload
     *
     * @return bool
     */
    public function update(array $payload): bool
    {
        ksort($payload);

        $response = $this->api->getClient()->request('PUT', $this->apiUrl(), [
            'form_params' => $payload,
        ]);

        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['server'])) {
            return false;
        }

        $this->data = $json['server'];

        return true;
    }

    /**
     * Reboot the server.
     *
     * @return bool
     */
    public function reboot(): bool
    {
        $this->api->getClient()->request('POST', $this->apiUrl('reboot'));

        return true;
    }

    /**
     * Revoke Forge access to server.
     *
     * @return bool
     **/
    public function revokeAccess(): bool
    {
        $this->api->getClient()->request('POST', $this->apiUrl('/revoke'));

        return true;
    }

    /**
     * Reconnect revoked server.
     *
     * @return string Public SSH key.
     */
    public function reconnect(): string
    {
        $response = $this->api->getClient()->request('POST', $this->apiUrl('/reconnect'));
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['public_key'])) {
            throw new PublicKeyWasNotFound(
                'Public key was not found after reconnecting revoked server (ID: '.$this->id().').',
                404
            );
        }

        return $json['public_key'];
    }

    /**
     * Reactivate revoked server. Make sure you've installed public SSH key
     * before calling this method.
     *
     * @return bool
     */
    public function reactivate(): bool
    {
        $this->api->getClient()->request('POST', $this->apiUrl('/reactivate'));

        return true;
    }

    /**
     * Delete the server.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $this->api->getClient()->request('DELETE', $this->apiUrl());

        return true;
    }
}
