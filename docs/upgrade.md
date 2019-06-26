# Upgrade from 1.x to 2.0.0

Version 2.0.0 introduced breaking change in library API in order to fix breaking change introduced by Forge API itself.

The `withMemoryOf` method of `\Laravel\Forge\Servers\Providers\Provider` has been removed because Forge changed the way
it handles server sizes across all providers & their regions.

Now you should refer to `/api/v1/regions` endpoint to list all available regions & server sizes that are available
in regions. Also [@acurrieclark](https://github.com/acurrieclark) made list of [provider regions](./provider-regions.md)
and [server sizes](./provider-sizes.md) but those lists may become outdated in future.

Now when you have your size identifier, use the `withSizeId` method to specify your new server's size:

```php
<?php

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Forge;

$forge = new Forge(new ApiProvider('api-token'));
$credential = $forge->credentialFor('ocean2');

// The "1" size ID is 1GB droplet with 1 CPU.
$server = $forge->create()->droplet()->withSizeId(1);
```
