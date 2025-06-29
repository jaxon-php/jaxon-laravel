<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;
use Jaxon\App\Ajax\AppInterface;
use Jaxon\Exception\SetupException;
use Jaxon\Laravel\App\Jaxon;
use Jaxon\Laravel\Middleware\AjaxMiddleware;
use Jaxon\Laravel\Middleware\ConfigMiddleware;

use function config;
use function config_path;
use function in_array;
use function is_string;
use function jaxon;
use function response;

class JaxonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register the Jaxon application
        jaxon()->di()->set(AppInterface::class, fn() => $this->app->make(Jaxon::class));

        // Config source and destination files
        $configSrcFile = __DIR__ . '/../config/config.php';
        $configDstFile = config_path('jaxon.php');

        // Publish assets and config
        $this->publishes([
            $configSrcFile => $configDstFile,
        ], 'config');

        /** \Illuminate\Routing\Router $router */
        $router = $this->app->make('router');

        // Register the middleware and route
        $router->aliasMiddleware('jaxon.config', ConfigMiddleware::class);
        $router->aliasMiddleware('jaxon.ajax', AjaxMiddleware::class);
        $jaxonMiddlewares = config('jaxon.app.request.middlewares', []);
        if(!in_array('jaxon.config', $jaxonMiddlewares))
        {
            $jaxonMiddlewares[] = 'jaxon.config';
        }
        if(!in_array('jaxon.ajax', $jaxonMiddlewares))
        {
            $jaxonMiddlewares[] = 'jaxon.ajax';
        }
        $route = $router->post(config('jaxon.lib.core.request.uri'),
                fn() => response()->json([])) // This is not supposed to be executed.
            ->middleware($jaxonMiddlewares);
        if(is_string(($routeName = config('jaxon.app.request.route', null))))
        {
            $route->name($routeName);
        }
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
        $this->app->singleton(Jaxon::class, function() {
            $jaxon = new Jaxon();
            $jaxon->setup('');
            return $jaxon;
        });
    }
}
