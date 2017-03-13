# Initialize SDK

Most of SDK classes depends on `Laravel\Forge\ApiProvider` class. Let's create new provider with your Forge API token:

```php
<?php

use Laravel\Forge\ApiProvider;

$api = new ApiProvider('token');
```

This instance will be used across all entities of SDK that requires API access. It won't produce any HTTP calls until you execute some real API methods.

[Back to Table of Contents](./readme.md)
