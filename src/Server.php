<?php

namespace Laravel\Forge;

use Laravel\Forge\Exceptions\Servers\PublicKeyWasNotFound;
use Laravel\Forge\Exceptions\Servers\ServerWasNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Laravel\Forge\Contracts\ResourceContract;

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
     * @throws ServerWasNotFoundException
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
     *
     * @return string|null
     */
    public function privateIp()
    {
        return $this->getData('private_ip_address');
    }

    /**
     * Server sudo password - only set on server save.
     *
     * @return string|null
     */
    public function sudoPassword()
    {
        return $this->getData('sudo_password');
    }

    /**
     * Server sudo password - only set on server save.
     *
     * @return string|null
     */
    public function databasePassword()
    {
        return $this->getData('database_password');
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
    public function papertrailStatus()
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
     * Server tags.
     *
     * @return array
     */
    public function tags()
    {
        return $this->getData('tags');
    }

    /**
     * Server ssh port.
     *
     * @return int
     */
    public function sshPort()
    {
        return $this->getData('ssh_port');
    }

    /**
     * The server provider.
     *
     * @return string
     */
    public function provider()
    {
        return $this->getData('provider');
    }

    /**
     * The server provider id.
     *
     * @return string
     */
    public function providerId()
    {
        return $this->getData('provider_id');
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
     * Enable PHP OPCache on the server.
     *
     * @return bool
     */
    public function enableOPCache(): bool
    {
        $this->getHttpClient()->request('POST', $this->apiUrl('/php/opcache'));

        return true;
    }

    /**
     * Disable PHP OPCache on the server.
     *
     * @return bool
     */
    public function disableOPCache(): bool
    {
        $this->getHttpClient()->request('DELETE', $this->apiUrl('/php/opcache'));

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

    /**
     * Create new Resource instance from HTTP response.
     *
     * @param \Psr\Http\Message\ResponseInterface       $response
     * @param \Laravel\Forge\ApiProvider                $api
     * @param \Laravel\Forge\Contracts\ResourceContract $owner    = null
     *
     * @return static
     */
    public static function createFromResponse(ResponseInterface $response, ApiProvider $api, ResourceContract $owner = null)
    {
        $json = json_decode((string) $response->getBody(), true);
        $result = $json['server'] ?? null;

        if (is_null($result)) {
            static::throwNotFoundException();
        }

        if (!empty($json['sudo_password'])) {
            $result['sudo_password'] = $json['sudo_password'];
        }

        if (!empty($json['database_password'])) {
            $result['database_password'] = $json['database_password'];
        }

        return new static($api, $result, $owner);
    }
}
