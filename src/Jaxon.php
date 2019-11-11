<?php

namespace Jaxon\Laravel;

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
            ($route = config('jaxon.app.request.route', null)))
        {
            $this->bootstrap()->uri(route($route));
        }

        $viewManager = $di->getViewmanager();
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

        // Set the framework di container wrapper
        $di->setAppContainer(new Container());

        $this->bootstrap()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, asset('jaxon/js'), public_path('jaxon/js'), !$bIsDebug)
            ->run(false);

        // Prevent the Jaxon library from sending the response or exiting
        $jaxon->setOption('core.response.send', false);
        $jaxon->setOption('core.process.exit', false);
    }

    /**
     * Process an incoming Jaxon request, and return the response.
     *
     * @return mixed
     */
    public function processRequest()
    {
        $jaxon = jaxon();
        // Process the jaxon request
        $jaxon->processRequest();
        // Get the reponse to the request
        $jaxonResponse = $jaxon->di()->getResponseManager()->getResponse();

        // Create and return a Laravel HTTP response
        $code = '200';
        $httpResponse = response($jaxonResponse->getOutput(), $code);
        $httpResponse->header('Content-Type', $jaxonResponse->getContentType() .
            ';charset="' . $jaxonResponse->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
