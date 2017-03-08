<?php

namespace Laravel\Forge;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ApiProvider
{
    /**
     * Base API URI.
     *
     * @var string
     */
    const BASE_URI = 'https://forge.laravel.com';

    /**
     * API Token.
     *
     * @var string
     */
    private $token;

    /**
     * Creates new API Provider instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Returns HTTP Client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient(): ClientInterface
    {
        if (!is_null($this->client)) {
            return $this->client;
        }

        return $this->client = $this->createClient();
    }

    /**
     * Returns API Token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Creates HTTP Client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function createClient(): ClientInterface
    {
        $client = new Client([
            'base_uri' => static::BASE_URI,
            'headers' => [
                'Authorization' => 'Bearer '.$this->getToken(),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $client;
    }
}
