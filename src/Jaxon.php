<?php

namespace Jaxon\Laravel;

use Illuminate\Support\Facades\Log;

class Jaxon
{
    use \Jaxon\Features\App;

    public function setup()
    {
        // Load Jaxon config settings
        $aLibOptions = config('jaxon.lib', []);
        $aAppOptions = config('jaxon.app', []);
        $bIsDebug = config('app.debug', false);

        // Jaxon library settings
        $jaxon = jaxon();
        $di = $jaxon->di();

        // The request URI can be set with a named route
        if(!config('jaxon.lib.core.request.uri') &&
            ($route = config('jaxon.app.request.route', 'jaxon')))
        {
            $this->bootstrap()->uri(route($route));
        }

        $viewManager = $di->getViewManager();
        // Set the default view namespace
        $viewManager->addNamespace('default', '', '', 'blade');
        // Add the view renderer
        $viewManager->addRenderer('blade', function () {
            return new View();
        });

        // Set the session manager
        $di->setSessionManager(function () {
            return new Session();
        });

        // Set the framework service container wrapper
        $di->setAppContainer(new Container());

        // Set the logger
        $this->setLogger(Log::getLogger());

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, asset('jaxon/js'), public_path('jaxon/js'), !$bIsDebug)
            ->run();

        // Prevent the Jaxon library from sending the response or exiting
        $jaxon->setOption('core.response.send', false);
        $jaxon->setOption('core.process.exit', false);
    }

    /**
     * Get the HTTP response
     *
     * @param string    $code       The HTTP response code
     *
     * @return mixed
     */
    public function httpResponse($code = '200')
    {
        $jaxon = jaxon();
        // Get the reponse to the request
        $jaxonResponse = $jaxon->di()->getResponseManager()->getResponse();
        if(!$jaxonResponse)
        {
            $jaxonResponse = $jaxon->getResponse();
        }

        // Create and return a Laravel HTTP response
        $httpResponse = response($jaxonResponse->getOutput(), $code);
        $httpResponse->header('Content-Type', $jaxonResponse->getContentType() .
            ';charset="' . $jaxonResponse->getCharacterEncoding() . '"');
        return $httpResponse;
    }

    /**
     * Process an incoming Jaxon request, and return the response.
     *
     * @return mixed
     */
    public function processRequest()
    {
        // Process the jaxon request
        jaxon()->processRequest();

        // Return the reponse to the request
        return $this->httpResponse();
    }
}
