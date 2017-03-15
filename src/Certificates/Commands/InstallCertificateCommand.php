<?php

namespace Laravel\Forge\Certificates\Commands;

class InstallCertificateCommand extends CertificateCommand
{
    /**
     * Set private key.
     *
     * @param string $privateKey
     *
     * @return static
     */
    public function usingPivateKey(string $privateKey)
    {
        return $this->attachPayload('key', $privateKey);
    }

    /**
     * Set certificate content.
     *
     * @param string $certificate
     *
     * @return static
     */
    public function usingCertificate(string $certificate)
    {
        return $this->attachPayload('certificate', $certificate);
    }
}
