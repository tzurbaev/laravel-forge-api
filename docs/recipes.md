# Overview

SDK provides Recipes Manager that allows you to create, list, delete and run recipes on your Forge servers.

# Usage

Documentation assumes that you've already created instance of `Forge` class.

All operations are performed via `Laravel\Forge\Recipes\RecipesManager` instance.

All methods return either instance of `Laravel\Forge\Recipes\Recipe` or array of `Laravel\Forge\Recipes\Recipe` instances.

## Create new recipe

```php
<?php

use Laravel\Forge\Forge;
use Laravel\Forge\ApiProvider;
use Laravel\Forge\Recipes\RecipesManager;

$forge = new Forge(new ApiProvider('api-token'));
$recipes = new RecipesManager();

$script = 'curl -sSL https://agent.digitalocean.com/install.sh | sh';
$recipe = $recipes->create('Install DigitalOceanAgent', $script)
    ->runningAs('root')
    ->on($forge);
```

## List recipes

```php
<?php

use Laravel\Forge\Recipes\RecipesManager;

$recipes = new RecipesManager();

$forgeRecipes = $recipes->list()->from($forge);

foreach ($forgeRecipes as $recipe) {
    echo 'Recipe "'.$recipe->name().'" has "'.$recipe->script().'" contents and is running as '.$recipe->user().' user.';
}
```

## Get single recipe by ID

```php
<?php

use Laravel\Forge\Recipes\RecipesManager;

$recipes = new RecipesManager();

$recipeId = 1234;
$recipe = $recipes->get($recipeId)->from($forge);

echo 'Recipe "'.$recipe->name().'" has "'.$recipe->script().'" contents and is running as '.$recipe->user().' user.';
```

## Update recipe

```php
<?php

use Laravel\Forge\Recipes\RecipesManager;

$recipes = new RecipesManager();
$recipe = $recipes->get(1234)->from($forge);

$recipe->update([
    'name' => 'Say OK',
    'script' => 'echo OK',
    'user' => 'forge',
]);
```

## Run recipe

```php
<?php

use Laravel\Forge\Recipes\RecipesManager;

$recipes = new RecipesManager();
$recipe = $recipes->get(1234)->from($forge);

$serverIds = [1234, 1235];
$recipe->run($serverIds);
```

## Delete recipe

```php
<?php

use Laravel\Forge\Recipes\RecipesManager;

$recipes = new RecipesManager();
$recipe = $recipes->get(1234)->from($forge);

if ($recipe->delete()) {
    echo 'Recipe "'.$recipe->name().'" was deleted.';
}
```

[Back to Table of Contents](./readme.md)
