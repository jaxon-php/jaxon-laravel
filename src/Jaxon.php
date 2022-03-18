<?php

namespace Jaxon\Laravel;

use Illuminate\Support\Facades\Log;

use function config;
use function route;
use function asset;
use function public_path;
use function response;
use function jaxon;

class Jaxon
{
    use \Jaxon\App\AppTrait;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->jaxon = jaxon();
    }

    public function setup()
    {
        // Set the default view namespace
        $this->addViewNamespace('default', '', '', 'blade');
        // Add the view renderer
        $this->addViewRenderer('blade', function () {
            return new View();
        });

        // Set the session manager
        $this->setSessionManager(function () {
            return new Session();
        });
        // Set the framework service container wrapper
        $this->setAppContainer(new Container());
        // Set the logger
        $this->setLogger(Log::getLogger());

        // The request URI can be set with a named route
        if(!config('jaxon.lib.core.request.uri') &&
            ($route = config('jaxon.app.request.route', 'jaxon')))
        {
            $this->bootstrap()->uri(route($route));
        }

        // Load Jaxon config settings
        $aLibOptions = config('jaxon.lib', []);
        $aAppOptions = config('jaxon.app', []);
        $bIsDebug = config('app.debug', false);

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, asset('jaxon/js'), public_path('jaxon/js'), !$bIsDebug)
            ->setup();
    }

    /**
     * @inheritDoc
     */
    public function httpResponse(string $sCode = '200')
    {
        // Get the reponse to the request
        $jaxonResponse = $this->jaxon->getResponse();

        // Create and return a Laravel HTTP response
        $httpResponse = response($jaxonResponse->getOutput(), $sCode);
        $httpResponse->header('Content-Type', $jaxonResponse->getContentType() .
            ';charset="' . $this->jaxon->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
