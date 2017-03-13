# Overview

SDK provides Firewall Manager that allows you to create, list and delete firewall rules on your Forge servers.

# Usage

Documentation assumes that you've already retrieved server instance from `ForgeServers` collection.

All operations are performed via `Laravel\Forge\Firewall\FirewallManager` instance.

All methods return either instance of `Laravel\Forge\Firewall\FirewallRule` or array of `Laravel\Forge\Firewall\FirewallRule` instances.

## Create new rule

```php
<?php

use Laravel\Forge\Firewall\FirewallManager;

$firewall = new FirewallManager();

$rule = $firewall->create('rule-name')->usingPort(88)->on($server);
```

## List rules

```php
<?php

use Laravel\Forge\Firewall\FirewallManager;

$firewall = new FirewallManager();

$rules = $firewall->list()->from($server);

foreach ($rules as $rule) {
    echo 'Rule '.$rule->name().' is using port #'.$rule->port().'.';
}
```

## Get single rule by ID

```php
<?php

use Laravel\Forge\Firewall\FirewallManager;

$firewall = new FirewallManager();

$ruleId = 1234;
$rule = $firewall->get($ruleId)->from($server);

echo 'Rule '.$rule->name().' is using port #'.$rule->port().'.';
```

## Delete rule

```php
<?php

use Laravel\Forge\Firewall\FirewallManager;

$firewall = new FirewallManager();

$ruleId = 1234;
$rule = $firewall->get($ruleId)->from($server);

if ($rule->delete()) {
    echo 'Rule '.$rule->name().' was deleted.';
}
```

[Back to Table of Contents](./readme.md)
