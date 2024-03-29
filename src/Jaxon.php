<?php

namespace Jaxon\Laravel;

use Jaxon\App\AppInterface;
use Jaxon\App\Traits\AppTrait;
use Jaxon\Exception\SetupException;
use Illuminate\Support\Facades\Log;

use function config;
use function route;
use function asset;
use function public_path;
use function response;
use function Jaxon\jaxon;

class Jaxon implements AppInterface
{
    use AppTrait;

    /**
     * The class constructor
     */
    public function __construct()
    {
        $this->initApp(jaxon()->di());
    }

    /**
     * @inheritDoc
     * @throws SetupException
     */
    public function setup(string $sConfigFile)
    {
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
