<?php

namespace Laravel\Forge\Recipes\Commands;

class CreateRecipeCommand extends RecipeCommand
{
    /**
     * Set recipe name.
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
     * Set script content.
     *
     * @param string $script
     *
     * @return static
     */
    public function usingScript(string $script)
    {
        return $this->attachPayload('script', $script);
    }

    /**
     * Set script user.
     *
     * @param string $user
     *
     * @return static
     */
    public function runningAs(string $user)
    {
        return $this->attachPayload('user', $user);
    }
}
