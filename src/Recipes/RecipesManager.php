<?php

namespace Laravel\Forge\Recipes;

use Laravel\Forge\Recipes\Commands\GetRecipeCommand;
use Laravel\Forge\Recipes\Commands\ListRecipesCommand;
use Laravel\Forge\Recipes\Commands\CreateRecipeCommand;

class RecipesManager
{
    /**
     * Initialize new create recipe command.
     *
     * @param string $name
     * @param string $script
     *
     * @return \Laravel\Forge\Recipes\Commands\CreateRecipeCommand
     */
    public function create(string $name, string $script)
    {
        return (new CreateRecipeCommand())
            ->identifiedAs($name)
            ->usingScript($script);
    }

    /**
     * Initialize new list recipes command.
     *
     * @return \Laravel\Forge\Recipes\Commands\ListRecipesCommand
     */
    public function list()
    {
        return new ListRecipesCommand();
    }

    /**
     * Initialize new get recipe command.
     *
     * @param int $recipeId
     *
     * @return \Laravel\Forge\Recipes\Commands\GetRecipeCommand
     */
    public function get(int $recipeId)
    {
        return (new GetRecipeCommand())->setResourceId($recipeId);
    }
}
