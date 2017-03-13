<?php

namespace Laravel\Forge\Laravel;

use Laravel\Forge\ApiProvider;
use Laravel\Forge\Forge;
use Illuminate\Support\ServiceProvider;
use Laravel\Forge\Servers\Factory;

class ForgeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->singleton(Forge::class, function ($app) {
            $token = $app['config']->get('forge.token');
            $forge = new Forge(new ApiProvider($token));

            // Set default credentials (if any exists).
            $defaultCredentials = $app['config']->get('forge.default_credentials', []);

            if (!count($defaultCredentials)) {
                return $forge;
            }

            foreach ($defaultCredentials as $provider => $credential) {
                Factory::setDefaultCredential($provider, $credential);
            }

            return $forge;
        });

        // Publish configuration file.
        $this->publishes([
            __DIR__.'/configs/forge.php' => config_path('forge.php'),
        ]);

        // Register console commands.
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\ForgeCredentials::class,
                Commands\ForgeServers::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
