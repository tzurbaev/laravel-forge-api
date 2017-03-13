<?php

namespace Laravel\Forge;

use Laravel\Forge\Exceptions\Servers\PublicKeyWasNotFound;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;

class Server extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'server';
    }

    /**
     * Resource path (relative to owner or API root).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'servers';
    }

    /**
     * Throw HTTP Not Found exception.
     *
     * @throws \Exception
     */
    protected static function throwNotFoundException()
    {
        throw new ServerWasNotFoundException('Given response is not a server response.');
    }

    /**
     * Credential ID.
     *
     * @return int
     */
    public function credentialId(): int
    {
        return intval($this->getData('credential_id'));
    }

    /**
     * Human readable server size.
     *
     * @return string|null
     */
    public function size()
    {
        return $this->getData('size');
    }

    /**
     * Server region.
     *
     * @return string|null
     */
    public function region()
    {
        return $this->getData('region');
    }

    /**
     * Server's PHP version.
     *
     * @return string|null
     */
    public function phpVersion()
    {
        return $this->getData('php_version');
    }

    /**
     * Server public IP address.
     *
     * @return string|null
     */
    public function ip()
    {
        return $this->getData('ip_address');
    }

    /**
     * Server private IP address.
     */
    public function privateIp()
    {
        return $this->getData('private_ip_address');
    }

    /**
     * Blackfire service status.
     *
     * @return string|null
     */
    public function blackfireStatus()
    {
        return $this->getData('blackfire_status');
    }

    /**
     * Papertrail service status.
     *
     * @return string
     */
    public function parerTrailStatus()
    {
        return $this->getData('papertrail_status');
    }

    /**
     * Determines if server access was revoked from Forge.
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return intval($this->getData('revoked')) === 1;
    }

    /**
     * Determines if server was provisioned and ready to use.
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return intval($this->getData('is_ready')) === 1;
    }

    /**
     * Network status.
     *
     * @return array|null
     */
    public function network()
    {
        return $this->getData('network');
    }

    /**
     * Reboot the server.
     *
     * @return bool
     */
    public function reboot(): bool
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('reboot'));

        return true;
    }

    /**
     * Revoke Forge access to server.
     *
     * @return bool
     **/
    public function revokeAccess(): bool
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('/revoke'));

        return true;
    }

    /**
     * Reconnect revoked server.
     *
     * @return string Public SSH key.
     */
    public function reconnect(): string
    {
        $response = $this->getHttpClient()->request('POST', $this->apiUrl('/reconnect'));
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
        $this->getHttpClient()->request('POST', $this->apiUrl('/reactivate'));

        return true;
    }
}
