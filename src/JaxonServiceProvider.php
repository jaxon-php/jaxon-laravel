<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;

class JaxonServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->app->singleton('jaxon', function ($app)
        {
            return new Jaxon();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'jaxon'
        );
    }
}
