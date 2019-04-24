<?php

namespace Laravel\Forge\Users;

use Laravel\Forge\ApiResource;

class User extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'user';
    }

    /**
     * Resource path (relative to owner or API root).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'user';
    }

    /**
     * Get user ID.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * Get user name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * Get user email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * Get card's last four numbers.
     *
     * @return string|null
     */
    public function getCardLastFour()
    {
        return $this->getData('card_last_four');
    }

    /**
     * Determines whether the user is connected to GitHub.
     *
     * @return bool
     */
    public function connectedToGitHub(): bool
    {
        return boolval($this->getData('connected_to_github')) === true;
    }

    /**
     * Determines whether the user is connected to GitLab.
     *
     * @return bool
     */
    public function connectedToGitLab(): bool
    {
        return boolval($this->getData('connected_to_gitlab')) === true;
    }

    /**
     * Determines whether the user is connected to Bitbucket.
     *
     * @return bool
     */
    public function connectedToBitbucket(): bool
    {
        return boolval($this->getData('connected_to_bitbucket')) === true;
    }

    /**
     * Determines whether the user is connected to Bitbucket V2.
     *
     * @return bool
     */
    public function connectedToBitbucketTwo(): bool
    {
        return boolval($this->getData('connected_to_bitbucket_two')) === true;
    }

    /**
     * Determines whether the user is connected to DigitalOcean.
     *
     * @return bool
     */
    public function connectedToDigitalOcean(): bool
    {
        return boolval($this->getData('connected_to_digitalocean')) === true;
    }

    /**
     * Determines whether the user is connected to Linode.
     *
     * @return bool
     */
    public function connectedToLinode(): bool
    {
        return boolval($this->getData('connected_to_linode')) === true;
    }

    /**
     * Determines whether the user is connected to Vultr.
     *
     * @return bool
     */
    public function connectedToVultr(): bool
    {
        return boolval($this->getData('connected_to_vultr')) === true;
    }

    /**
     * Determines whether the user is connected to Aws.
     *
     * @return bool
     */
    public function connectedToAws(): bool
    {
        return boolval($this->getData('connected_to_aws')) === true;
    }

    /**
     * Determines whether the user has active Forge subscription.
     *
     * @return bool
     */
    public function subscribed(): bool
    {
        return boolval($this->getData('subscribed')) === true;
    }

    /**
     * Determines whether the user can create new servers.
     *
     * @return bool
     */
    public function canCreateServers(): bool
    {
        return boolval($this->getData('can_create_servers')) === true;
    }
}
