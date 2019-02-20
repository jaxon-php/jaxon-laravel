<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;

class JaxonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Config source and destination files
        $configSrcFile = __DIR__ . '/../config/config.php';
        $configDstFile = config_path('jaxon.php');
        // Publish assets and config
        $this->publishes([
            $configSrcFile => $configDstFile,
        ], 'config');
        // Load package routes
        if(!$this->app->routesAreCached())
        {
            require(__DIR__ . '/Http/routes.php');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the Jaxon singleton
        $this->app->resolving(Jaxon::class, function (Jaxon $jaxon)
        {
            $jaxon->init();
        });
        $this->app->singleton(Jaxon::class, Jaxon::class);
        // Define the alias
        $this->app->alias(Jaxon::class, 'jaxon');
    }
}
