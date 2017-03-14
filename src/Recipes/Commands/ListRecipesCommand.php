<?php

namespace Laravel\Forge\Recipes\Commands;

class ListRecipesCommand extends RecipeCommand
{
    /**
     * Items key for List response.
     *
     * @return string
     */
    public function listResponseItemsKey()
    {
        return 'recipes';
    }
}
