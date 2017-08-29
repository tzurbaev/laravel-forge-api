<?php

namespace Laravel\Forge\Sites\Commands;

class CreateSiteCommand extends SiteCommand
{
    /**
     * Set domain name.
     *
     * @param string $domain
     *
     * @return static
     */
    public function identifiedAs(string $domain)
    {
        return $this->attachPayload('domain', $domain);
    }

    /**
     * Indicates that site will be created as General PHP/Laravel Application.
     *
     * @return static
     */
    public function asPhp()
    {
        return $this->attachPayload('project_type', 'php');
    }

    /**
     * Identifies which web directory the public app will reside at
     *
     * @param string $directory
     *
     * @return static
     */
    public function withDirectory(string $directory)
    {
        return $this->attachPayload('directory', $directory);
    }

    /**
     * Indicates that site will be created as Static HTML site.
     *
     * @return static
     */
    public function asStatic()
    {
        return $this->attachPayload('project_type', 'html');
    }

    /**
     * Indicates that site will be created as Symfony Application.
     *
     * @return static
     */
    public function asSymfony()
    {
        return $this->attachPayload('project_type', 'symfony');
    }

    /**
     * Indicates that site will be created as Symfony (Dev) Application.
     *
     * @return static
     */
    public function asSymfonyDev()
    {
        return $this->attachPayload('project_type', 'symfony_dev');
    }

    /**
     * Alias for "asPhp" method.
     *
     * @return static
     */
    public function asLaravel()
    {
        return $this->asPhp();
    }

    /**
     * Alias for "asPhp" method.
     *
     * @return static
     */
    public function asGeneralPhp()
    {
        return $this->asPhp();
    }

    /**
     * Alias for "asStatic" method.
     *
     * @return static
     */
    public function asHtml()
    {
        return $this->asStatic();
    }
}
