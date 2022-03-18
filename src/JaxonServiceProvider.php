<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;

use function config_path;

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
            require(__DIR__ . '/../routes/web.php');
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
        $this->app->singleton(Jaxon::class, function () {
            $jaxon = new Jaxon();
            $jaxon->setup();
            return $jaxon;
        });
        // Define the alias
        $this->app->alias(Jaxon::class, 'jaxon');
    }
}
