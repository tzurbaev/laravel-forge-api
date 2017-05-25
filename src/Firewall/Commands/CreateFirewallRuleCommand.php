<?php

namespace Laravel\Forge\Firewall\Commands;

class CreateFirewallRuleCommand extends FirewallRuleCommand
{
    /**
     * Set rule name.
     *
     * @param string $name
     *
     * @return static
     */
    public function identifiedAs(string $name)
    {
        return $this->attachPayload('name', $name);
    }

    /**
     * Set rule port number.
     *
     * @param int $port
     *
     * @return static
     */
    public function usingPort(int $port)
    {
        return $this->attachPayload('port', $port);
    }

    /**
     * Set rule ip address.
     *
     * @param string $ipAddress
     *
     * @return static
     */
    public function usingIp(string $ipAddress)
    {
        return $this->attachPayload('ip_address', $ipAddress);
    }
}
