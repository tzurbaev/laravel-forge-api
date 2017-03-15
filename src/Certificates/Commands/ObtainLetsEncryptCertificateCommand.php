<?php

namespace Laravel\Forge\Certificates\Commands;

use InvalidArgumentException;

class ObtainLetsEncryptCertificateCommand extends CertificateCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'letsencrypt';
    }

    /**
     * Set LetsEncrypt certificate domains.
     *
     * @param string|array $domain
     *
     * @return static
     */
    public function usingDomains($domains)
    {
        if (is_string($domains)) {
            $domains = [$domains];
        } elseif (!is_array($domains)) {
            throw new InvalidArgumentException('Domains must be a string or array.');
        }

        return $this->attachPayload('domains', $domains);
    }
}
