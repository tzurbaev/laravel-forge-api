<?php

namespace Laravel\Forge\Certificates;

use Laravel\Forge\Certificates\Commands\GetCertificateCommand;
use Laravel\Forge\Certificates\Commands\CloneCertificateCommand;
use Laravel\Forge\Certificates\Commands\ListCertificatesCommand;
use Laravel\Forge\Certificates\Commands\CreateCertificateCommand;
use Laravel\Forge\Certificates\Commands\InstallCertificateCommand;
use Laravel\Forge\Certificates\Commands\ObtainLetsEncryptCertificateCommand;

class CertificatesManager
{
    /**
     * Initialize new create certificate command.
     *
     * @param string $domain
     *
     * @return \Laravel\Forge\Certificates\Commands\CreateCertificateCommand
     */
    public function create(string $domain)
    {
        return (new CreateCertificateCommand())
            ->operationType('new')
            ->identifiedAs($domain);
    }

    /**
     * Initialize new install certificate command.
     *
     * @param string $privateKey
     * @param string $certificate
     *
     * @return \Laravel\Forge\Certificates\Commands\InstallCertificateCommand
     */
    public function install(string $privateKey, string $certificate)
    {
        return (new InstallCertificateCommand())
            ->operationType('existing')
            ->usingPivateKey($privateKey)
            ->usingCertificate($certificate);
    }

    /**
     * Initialize new clone certificate command.
     *
     * @param int $certificateId
     *
     * @return \Laravel\Forge\Certificates\Commands\CloneCertificateCommand
     */
    public function clone(int $certificateId)
    {
        return (new CloneCertificateCommand())
            ->operationType('clone')
            ->usingCertificateId($certificateId);
    }

    /**
     * Initialize new obtain Let's Encrypt certificate command.
     *
     * @param string|array $domains
     *
     * @return \Laravel\Forge\Certificates\Commands\ObtainLetsEncryptCertificateCommand
     */
    public function obtain($domains)
    {
        return (new ObtainLetsEncryptCertificateCommand())
            ->usingDomains($domains);
    }

    /**
     * Initialize new list certificates command.
     *
     * @return \Laravel\Forge\Certificates\Commands\ListCertificatesCommand
     */
    public function list()
    {
        return new ListCertificatesCommand();
    }

    /**
     * Initialize new get certificate command.
     *
     * @param int $certificateId
     *
     * @return \Laravel\Forge\Certificates\Commands\GetCertificateCommand
     */
    public function get(int $certificateId)
    {
        return (new GetCertificateCommand())->setResourceId($certificateId);
    }
}
