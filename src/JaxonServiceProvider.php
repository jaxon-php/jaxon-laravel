<?php

namespace Jaxon\Laravel;

use Illuminate\Support\ServiceProvider;
use Jaxon\App\AppInterface;
use Jaxon\Exception\SetupException;
use Jaxon\Laravel\Middleware\AjaxMiddleware;

use function config;
use function config_path;
use function Jaxon\jaxon;
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
        $jaxonMiddleware = 'jaxon.ajax';
        $router->aliasMiddleware($jaxonMiddleware, AjaxMiddleware::class);
        if(!is_string(($jaxonRoute = config('jaxon.app.request.route', null))))
        {
            return;
        }

        $jaxonMiddlewares = config('jaxon.app.request.middlewares', []);
        if(!in_array($jaxonMiddleware, $jaxonMiddlewares))
        {
            $jaxonMiddlewares[] = $jaxonMiddleware;
        }
        $router->post($jaxonRoute, function () {
            return response()->json([]); // This is not supposed to be executed.
        })->middleware($jaxonMiddlewares)->name('jaxon');
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
