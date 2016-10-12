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
            // Jaxon application config
            $requestRoute = config('jaxon.app.route', 'jaxon');
            $controllerDir = config('jaxon.app.dir', app_path('Jaxon/Controllers'));
            $namespace = config('jaxon.app.namespace', '\\Jaxon\\App');

            $excluded = config('jaxon.app.excluded', array());
            // The public methods of the Controller base class must not be exported to javascript
            $controllerClass = new \ReflectionClass('\\Jaxon\\Laravel\\Controller');
            foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
            {
                $excluded[] = $xMethod->getShortName();
            }

            $jaxon = jaxon();
            // Use the Composer autoloader
            $jaxon->useComposerAutoloader();
            // Jaxon library default options
            $jaxon->setOptions(array(
                'js.app.extern' => !config('app.debug', false),
                'js.app.minify' => !config('app.debug', false),
                'js.app.uri' => asset('jaxon/js'),
                'js.app.dir' => public_path('jaxon/js'),
            ));
            // Jaxon library user options
            $jaxon->readConfigFile(base_path('config/jaxon.php'), 'lib');
            // The request URI can be set with a Laravel route
            if(!$jaxon->hasOption('core.request.uri'))
            {
                $jaxon->setOption('core.request.uri', route($requestRoute));
            }
            // Register the default Jaxon class directory
            $jaxon->addClassDir($controllerDir, $namespace, $excluded);

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
