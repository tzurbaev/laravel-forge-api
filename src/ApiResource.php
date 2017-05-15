<?php

namespace Laravel\Forge;

use ArrayAccess;
use InvalidArgumentException;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Laravel\Forge\Traits\ArrayAccessTrait;
use Laravel\Forge\Contracts\ResourceContract;
use Laravel\Forge\Exceptions\Resources\DeleteResourceException;
use Laravel\Forge\Exceptions\Resources\UpdateResourceException;

abstract class ApiResource implements ArrayAccess, ResourceContract
{
    use ArrayAccessTrait;

    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var \Laravel\Forge\Contracts\ResourceContract
     */
    protected $owner;

    /**
     * Create new resource instance.
     *
     * @param \Laravel\Forge\ApiProvider                $api   = null
     * @param array                                     $data  = []
     * @param \Laravel\Forge\Contracts\ResourceContract $owner
     */
    public function __construct(ApiProvider $api = null, array $data = [], ResourceContract $owner = null)
    {
        $this->api = $api;
        $this->data = $data;
        $this->owner = $owner;
    }

    /**
     * Resource type.
     *
     * @return string
     */
    abstract public static function resourceType();

    /**
     * Resource path (relative to owner or API root).
     *
     * @return string
     */
    abstract public function resourcePath();

    /**
     * Create new Resource instance from HTTP response.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\ApiProvider                $api
     * @param \Laravel\Forge\Contracts\ResourceContract $owner    = null
     */
    public static function createFromResponse(ResponseInterface $response, ApiProvider $api, ResourceContract $owner = null)
    {
        $json = json_decode((string) $response->getBody(), true);
        $resourceType = static::resourceType();

        if (empty($json[$resourceType])) {
            static::throwNotFoundException();
        }

        return new static($api, $json[$resourceType], $owner);
    }

    /**
     * Throw HTTP Not Found exception.
     *
     * @throws \Exception
     */
    protected static function throwNotFoundException()
    {
        throw new InvalidArgumentException('Given response is not a '.static::resourceType().' response.');
    }

    /**
     * Determines if current resource has an owner.
     *
     * @return bool
     */
    public function hasResourceOwner(): bool
    {
        return !is_null($this->owner);
    }

    /**
     * Get current resource owner.
     *
     * @return \Laravel\Forge\ApiResource|null
     */
    public function resourceOwner()
    {
        return $this->owner;
    }

    /**
     * Get API provider.
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public function getApi(): ApiProvider
    {
        return $this->api;
    }

    /**
     * Get underlying API provider's HTTP client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->api->getClient();
    }

    /**
     * Get resource data.
     *
     * @param string|int $key
     * @param mixed      $default = null
     *
     * @return mixed|null
     */
    public function getData($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Resource API URL.
     *
     * @param string $path            = ''
     * @param bool   $withPropagation = true
     *
     * @return string
     */
    public function apiUrl(string $path = '', bool $withPropagation = true): string
    {
        $path = ($path ? '/'.ltrim($path, '/') : '');
        $resourcePath = rtrim($this->resourcePath(), '/').'/'.$this->id().$path;

        if (!$this->hasResourceOwner() || !$withPropagation) {
            return $resourcePath;
        }

        return $this->resourceOwner()->apiUrl($resourcePath);
    }

    /**
     * Resource ID.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->getData('id', 0));
    }

    /**
     * Resource name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->getData('name');
    }

    /**
     * Resource status.
     *
     * @return string|null
     */
    public function status()
    {
        return $this->getData('status');
    }

    /**
     * Get resource creation date.
     *
     * @return string|null
     */
    public function createdAt()
    {
        return $this->getData('created_at');
    }

    /**
     * Update resource data.
     *
     * @throws UpdateResourceException
     *
     * @return bool
     */
    public function update(array $payload): bool
    {
        $resourceType = static::resourceType();

        try {
            $response = $this->getHttpClient()->request('PUT', $this->apiUrl(), [
                'json' => $payload,
            ]);
        } catch (RequestException $e) {
            $this->throwResourceException($e->getResponse(), 'update', UpdateResourceException::class);
        }

        $json = json_decode((string) $response->getBody(), true);

        if (empty($json[$resourceType])) {
            return false;
        }

        $this->data = $json[$resourceType];

        return true;
    }

    /**
     * Delete current resource.
     *
     * @throws \Laravel\Forge\Exceptions\Resources\DeleteResourceException
     *
     * @return bool
     */
    public function delete()
    {
        try {
            $this->getHttpClient()->request('DELETE', $this->apiUrl());
        } catch (RequestException $e) {
            $this->throwResourceException($e->getResponse(), 'delete', DeleteResourceException::class);
        }

        return true;
    }

    /**
     * Throw resource exception.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string                              $action
     * @param string                              $exceptionClass
     *
     * @throws \Exception
     */
    protected function throwResourceException(ResponseInterface $response, string $action, string $exceptionClass)
    {
        $message = 'Unable to '.$action.' resource (type: '.static::resourceType().', ID: '.$this->id().').';

        if (is_null($response)) {
            throw new InvalidArgumentException($message);
        }

        $message .= ' Server response: "'.((string) $response->getBody()).'".';

        throw new $exceptionClass($message, $response->getStatusCode());
    }
}
