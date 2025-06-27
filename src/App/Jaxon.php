<?php

namespace Jaxon\Laravel\App;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Jaxon\App\Ajax\AbstractApp;
use Jaxon\Exception\SetupException;

use function asset;
use function config;
use function jaxon;
use function public_path;
use function response;
use function route;

class Jaxon extends AbstractApp
{
    /**
     * @return void
     */
    private function createDirectives(): void
    {
        // Directives for Jaxon Js and CSS codes
        Blade::directive('jxnCss', fn() => '<?= Jaxon\jaxon()->css(); ?>');
        Blade::directive('jxnJs', fn() => '<?= Jaxon\jaxon()->js(); ?>');
        Blade::directive('jxnScript', fn($expr) => "<?= Jaxon\jaxon()->script($expr); ?>");

        // Directives for Jaxon custom attributes
        Blade::directive('jxnBind', fn($expr) => "<?= Jaxon\attr()->bind($expr); ?>");
        Blade::directive('jxnHtml', fn($expr) => "<?= Jaxon\attr()->html($expr); ?>");
        Blade::directive('jxnPagination', fn($expr) => "<?= Jaxon\attr()->pagination($expr); ?>");
        Blade::directive('jxnOn', fn($expr) => "<?= Jaxon\attr()->on($expr); ?>");
        Blade::directive('jxnClick', fn($expr) => "<?= Jaxon\attr()->click($expr); ?>");
        Blade::directive('jxnEvent', fn($expr) => "<?= setJxnEvent($expr); ?>");
    }

    /**
     * Setup the Jaxon library
     *
     * @throws SetupException
     */
    public function setup(string $_ = ''): void
    {
        $this->createDirectives();

        // Add the view renderer
        $this->addViewRenderer('blade', '', fn() => new View());
        // Set the session manager
        $this->setSessionManager(fn() => new Session());
        // Set the framework service container wrapper
        $this->setContainer(new Container());
        // Set the logger
        $this->setLogger(Log::getLogger());

        $jaxon = jaxon();
        // The request URI can be set with a named route
        if(!config('jaxon.lib.core.request.uri') &&
            ($route = config('jaxon.app.request.route', 'jaxon')))
        {
            $jaxon->setUri(route($route));
        }

        // Load Jaxon config settings
        $aLibOptions = config('jaxon.lib', []);
        $aAppOptions = config('jaxon.app', []);
        $bExport = $bMinify = !config('app.debug', false);

        // Always load the global functions.
        $jaxon->setAppOption('helpers.global', true);

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            ->asset($bExport, $bMinify, asset('jaxon/js'), public_path('jaxon/js'))
            ->setup();
    }

    /**
     * @inheritDoc
     */
    public function httpResponse(string $sCode = '200'): mixed
    {
        // Create and return a Laravel HTTP response
        $httpResponse = response($this->ajaxResponse()->getOutput(), $sCode);
        $httpResponse->header('Content-Type', $this->getContentType());
        return $httpResponse;
    }
}
