<?php

namespace Laravel\Forge\Contracts;

use Laravel\Forge\ApiProvider;
use GuzzleHttp\ClientInterface;

interface ResourceContract
{
    /**
     * Get API provider.
     *
     * @return \Laravel\Forge\ApiProvider
     */
    public function getApi(): ApiProvider;

    /**
     * Get underlying API provider's HTTP client.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface;

    /**
     * Resource API URL.
     *
     * @param string $path            = ''
     * @param bool   $withPropagation = true
     *
     * @return string
     */
    public function apiUrl(string $path = '', bool $withPropagation = true): string;

    /**
     * Resource name.
     *
     * @return string|null
     */
    public function name();
}
