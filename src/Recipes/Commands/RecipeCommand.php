<?php

namespace Laravel\Forge\Recipes\Commands;

use Laravel\Forge\Recipes\Recipe;
use Laravel\Forge\Commands\ResourceCommand;

abstract class RecipeCommand extends ResourceCommand
{
    /**
     * Resource path.
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'recipes';
    }

    /**
     * Resource class name.
     *
     * @return string
     */
    public function resourceClass()
    {
        return Recipe::class;
    }
}
