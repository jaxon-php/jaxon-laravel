<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;
use Jaxon\App\AppInterface;
use Jaxon\Exception\SetupException;

use function config_path;
use function Jaxon\jaxon;

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
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @throws SetupException
     */
    public function register()
    {
        // Register the Jaxon singleton
        jaxon()->di()->set(AppInterface::class, function() {
            $jaxon = new Jaxon();
            $jaxon->setup('');
            return $jaxon;
        });
        // Register the Jaxon singleton
        $this->app->singleton(Jaxon::class, function() {
            return jaxon()->app();
        });
    }
}
