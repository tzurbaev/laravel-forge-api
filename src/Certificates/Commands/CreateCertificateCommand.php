<?php

namespace Laravel\Forge\Certificates\Commands;

class CreateCertificateCommand extends CertificateCommand
{
    /**
     * Set certificate domain.
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
     * Set certificate organization name.
     *
     * @param string $organization
     */
    public function ownedBy(string $organization)
    {
        return $this->attachPayload('organization', $organization);
    }

    /**
     * Set organization location.
     *
     * @param string $country
     * @param string $state
     * @param string $city
     *
     * @return static
     */
    public function locatedAt(string $country, string $state, string $city)
    {
        return $this
            ->attachPayload('country', $country)
            ->attachPayload('state', $state)
            ->attachPayload('city', $city);
    }

    /**
     * Set certificate department.
     *
     * @param string $department*
     *
     * @return static
     */
    public function assignedTo(string $department)
    {
        return $this->attachPayload('department', $department);
    }
}
