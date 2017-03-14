<?php

namespace Laravel\Forge\Recipes;

use Laravel\Forge\ApiResource;

class Recipe extends ApiResource
{
    /**
     * Resource type.
     *
     * @return string
     */
    public static function resourceType()
    {
        return 'recipe';
    }

    /**
     * Resource path (relative to Server URL).
     *
     * @return string
     */
    public function resourcePath()
    {
        return 'recipes';
    }

    /**
     * Recipe user.
     *
     * @return string|null
     */
    public function user()
    {
        return $this->getData('user');
    }

    /**
     * Recipe script.
     *
     * @return string|null
     */
    public function script()
    {
        return $this->getData('script');
    }

    /**
     * Run the recipe.
     *
     * @return bool
     */
    public function run()
    {
        $this->getHttpClient()->request('POST', 'recipes/'.$this->id().'/run');

        return true;
    }
}
