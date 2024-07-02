<?php

namespace Jaxon\Laravel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jaxon\App\AppInterface;
use Jaxon\Exception\SetupException;
use Jaxon\Laravel\Middleware\AjaxMiddleware;

use function config;
use function config_path;
use function Jaxon\jaxon;
use function preg_replace;
use function response;

class JaxonServiceProvider extends ServiceProvider
{
    /**
     * Replace Jaxon functions with their full names
     *
     * @param string $expression The directive parameter
     *
     * @return string
     */
    private function expr(string $expression)
    {
        return preg_replace('/([\(\s\,])(rq|jq|js|pm)\(/', '${1}\\Jaxon\\\${2}(', $expression);
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Directives for Jaxon custom attributes
        Blade::directive('jxnFunc', function ($expression) {
            return '<?php echo \Jaxon\attr()->func(' . $this->expr($expression) . '); ?>';
        });
        Blade::directive('jxnShow', function ($expression) {
            return '<?php echo \Jaxon\attr()->show(' . $this->expr($expression) . '); ?>';
        });
        Blade::directive('jxnHtml', function ($expression) {
            return '<?php echo \Jaxon\attr()->html(' . $this->expr($expression) . '); ?>';
        });

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

        // Register the Jaxon application
        jaxon()->di()->set(AppInterface::class, function() {
            return $this->app->make(Jaxon::class);
        });
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
