<?php

namespace Laravel\Forge\Daemons;

use ArrayAccess;
use InvalidArgumentException;
use Laravel\Forge\ApiProvider;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Traits\ArrayAccessTrait;

class Daemon implements ArrayAccess
{
    use ArrayAccessTrait;

    /**
     * @var \Laravel\Forge\ApiProvider
     */
    protected $api;

    /**
     * Daemon's Server ID.
     *
     * @var int
     */
    protected $serverId;

    /**
     * Create new daemon instance.
     *
     * @param \Laravel\Forge\ApiProvider $api
     * @param array                      $data
     */
    public function __construct(ApiProvider $api, array $data = [], int $serverId = 0)
    {
        $this->api = $api;
        $this->data = $data;
        $this->serverId = $serverId;
    }

    /**
     * Create new daemon instance from HTTP response.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Laravel\Forge\ApiProvider          $api
     * @param int                                 $serverId
     *
     * @return static
     */
    public static function createFromResponse(ResponseInterface $response, ApiProvider $api, int $serverId)
    {
        $json = json_decode((string) $response->getBody(), true);

        if (empty($json['daemon'])) {
            throw new InvalidArgumentException('Given response is not a daemon response.');
        }

        return new static($api, $json['daemon'], $serverId);
    }

    /**
     * Daemon ID.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->data['id']);
    }

    /**
     * Daemon command.
     *
     * @return string|null
     */
    public function command()
    {
        return $this->data['command'];
    }

    /**
     * Daemon user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->data['user'];
    }

    /**
     * Restart daemon.
     *
     * @return bool
     */
    public function restart()
    {
        $this->api->getClient()->request(
            'POST',
            'servers/'.$this->serverId.'/daemons/'.$this->id().'/restart'
        );

        return true;
    }

    /**
     * Delete daemon.
     *
     * @return bool
     */
    public function delete()
    {
        $this->api->getClient()->request('DELETE', 'servers/'.$this->serverId.'/daemons/'.$this->id());

        return true;
    }
}
