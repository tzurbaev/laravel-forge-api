<?php

namespace Laravel\Forge\Firewall;

use Laravel\Forge\Firewall\Commands\GetFirewallRuleCommand;
use Laravel\Forge\Firewall\Commands\ListFirewallRulesCommand;
use Laravel\Forge\Firewall\Commands\CreateFirewallRuleCommand;

class FirewallManager
{
    /**
     * Initialize new create firewall rule command.
     *
     * @param array $payload = []
     *
     * @return \Laravel\Forge\Firewall\Commands\CreateFirewallRuleCommand
     */
    public function create(array $payload = [])
    {
        return (new CreateFirewallRuleCommand())->withPayload($payload);
    }

    /**
     * Initialize new list firewall rules command.
     *
     * @return \Laravel\Forge\Firewall\Commands\ListFirewallRulesCommand
     */
    public function list()
    {
        return new ListFirewallRulesCommand();
    }

    /**
     * Initialize new get firewall rule command.
     *
     * @param int $daemonId
     *
     * @return \Laravel\Forge\Firewall\Commands\GetFirewallRuleCommand
     */
    public function get(int $daemonId)
    {
        return (new GetFirewallRuleCommand())->setItemId($daemonId);
    }
}
