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
            $this->jaxon()->uri(route($route));
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

        $this->jaxon()
            ->lib($aLibOptions)
            ->app($aAppOptions)
            // ->uri($sUri)
            ->js(!$bIsDebug, asset('jaxon/js'), public_path('jaxon/js'), !$bIsDebug)
            ->bootstrap(false);
    }

    /**
     * Wrap the Jaxon response into an HTTP response.
     *
     * @param  $code        The HTTP Response code
     *
     * @return HTTP Response
     */
    public function httpResponse($code = '200')
    {
        // Create and return a Laravel HTTP response
        $httpResponse = response($this->ajaxResponse()->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->ajaxResponse()->getContentType() .
            ';charset="' . $this->ajaxResponse()->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
