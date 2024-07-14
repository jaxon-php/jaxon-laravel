<?php

namespace Jaxon\Laravel\App;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Jaxon\App\AbstractApp;
use Jaxon\Exception\SetupException;

use function config;
use function route;
use function asset;
use function public_path;
use function preg_replace;
use function response;

class Jaxon extends AbstractApp
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
     * Setup the Jaxon library
     *
     * @throws SetupException
     */
    public function setup()
    {
        // Directives for Jaxon custom attributes
        Blade::directive('jxnHtml', function($expression) {
            return '<?php echo \Jaxon\attr()->html(' . $this->expr($expression) . '); ?>';
        });
        Blade::directive('jxnShow', function($expression) {
            return '<?php echo \Jaxon\attr()->show(' . $this->expr($expression) . '); ?>';
        });
        Blade::directive('jxnTarget', function($expression) {
            return '<?php echo \Jaxon\attr()->target(' . $expression . '); ?>';
        });
        Blade::directive('jxnOn', function($expression) {
            return '<?php echo \Jaxon\attr()->on(' . $this->expr($expression) . '); ?>';
        });

        // Directives for Jaxon Js and CSS codes
        Blade::directive('jxnCss', function() {
            return '<?php echo \Jaxon\jaxon()->css(); ?>';
        });
        Blade::directive('jxnJs', function() {
            return '<?php echo \Jaxon\jaxon()->js(); ?>';
        });
        Blade::directive('jxnScript', function($expression) {
            return '<?php echo \Jaxon\jaxon()->script(' . $expression . '); ?>';
        });

        // Add the view renderer
        $this->addViewRenderer('blade', '', function () {
            return new View();
        });
        // Set the session manager
        $this->setSessionManager(function () {
            return new Session();
        });
        // Set the framework service container wrapper
        $this->setContainer(new Container());
        // Set the logger
        $this->setLogger(Log::getLogger());

        // The request URI can be set with a named route
        if(!config('jaxon.lib.core.request.uri') && ($route = config('jaxon.app.request.route', 'jaxon')))
        {
            $this->uri(route($route));
        }

        // Load Jaxon config settings
        $aLibOptions = config('jaxon.lib', []);
        $aAppOptions = config('jaxon.app', []);
        $bExport = $bMinify = !config('app.debug', false);

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            ->asset($bExport, $bMinify, asset('jaxon/js'), public_path('jaxon/js'))
            ->setup();
    }

    /**
     * @inheritDoc
     */
    public function httpResponse(string $sCode = '200')
    {
        // Create and return a Laravel HTTP response
        $httpResponse = response($this->ajaxResponse()->getOutput(), $sCode);
        $httpResponse->header('Content-Type', $this->getContentType());

        return $httpResponse;
    }
}
