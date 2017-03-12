<?php

namespace Laravel\Forge\Firewall\Commands;

class ListFirewallRulesCommand extends FirewallRuleCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'rules';
    }
}
