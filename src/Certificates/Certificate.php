<?php

namespace Laravel\Forge\Certificates;

use Laravel\Forge\ApiResource;

class Certificate extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'certificate';
    }

    /**
     * Resource path (relative to owner or API root).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'certificates';
    }

    /**
     * Resource name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->domain();
    }

    /**
     * Certificate domain.
     *
     * @return string|null
     */
    public function domain()
    {
        return $this->getData('domain');
    }

    /**
     * Certificate type.
     *
     * @return string|null
     */
    public function type()
    {
        return $this->getData('type');
    }

    /**
     * Request status.
     *
     * @return string|null
     */
    public function requestStatus()
    {
        return $this->getData('request_status');
    }

    /**
     * Determines if certificate is active.
     *
     * @return bool
     */
    public function active(): bool
    {
        return intval($this->getData('active')) === 1;
    }

    /**
     * Determines if current certificate was installed from existing certificate.
     *
     * @return bool
     */
    public function existing(): bool
    {
        return intval($this->getData('existing')) === 1;
    }

    /**
     * Determines if current certificate is Let's Encrypt certificate.
     *
     * @return bool
     */
    public function letsencrypt(): bool
    {
        return $this->type() === 'letsencrypt';
    }

    /**
     * Get Certificate Signing Request value.
     *
     * @return string
     */
    public function csr()
    {
        $response = $this->getHttpClient()->request('GET', $this->apiUrl('/csr'));

        return (string) $response->getBody();
    }

    /**
     * Install certificate.
     *
     * @param string $content
     * @param bool   $addIntermediates = false
     *
     * @return bool
     */
    public function install(string $content, bool $addIntermediates = false): bool
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('/install'), [
            'json' => [
                'certificate' => $content,
                'add_intermediates' => $addIntermediates,
            ],
        ]);

        return true;
    }

    /**
     * Activate certificate.
     *
     * @return bool
     */
    public function activate(): bool
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('/activate'));

        return true;
    }
}
