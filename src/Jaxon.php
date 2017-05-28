<?php

namespace Jaxon\Laravel;

class Jaxon
{
    use \Jaxon\Sentry\Traits\Armada;

    /**
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function jaxonSetup()
    {
        // Load Jaxon config settings
        $libConfig = config('jaxon.lib', array());
        $appConfig = config('jaxon.app', array());

        // Jaxon library settings
        $jaxon = jaxon();
        $sentry = $jaxon->sentry();
        $jaxon->setOptions($libConfig);

        // Jaxon application settings
        $this->appConfig = $jaxon->newConfig();
        $this->appConfig->setOptions($appConfig);
        // The request URI can be set with a names route
        if(!$jaxon->hasOption('core.request.uri') &&
            ($route = $this->appConfig->getOption('request.route', null)) &&
            ($url = route($route)))
        {
            $jaxon->setOption('core.request.uri', $url);
        }

        // Jaxon library default settings
        $isDebug = config('app.debug', false);
        $sentry->setLibraryOptions(!$isDebug, !$isDebug, asset('jaxon/js'), public_path('jaxon/js'));

        // Set the default view namespace
        $sentry->addViewNamespace('default', '', '', 'blade');
        $this->appConfig->setOption('options.views.default', 'default');

        // Add the view renderer
        $sentry->addViewRenderer('blade', function(){
            return new View();
        });

        // Set the session manager
        $sentry->setSessionManager(function(){
            return new Session();
        });
    }

    /**
     * Set the module specific options for the Jaxon library.
     *
     * This method needs to set at least the Jaxon request URI.
     *
     * @return void
     */
    protected function jaxonCheck()
    {
        // Todo: check the mandatory options
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
        $httpResponse = \Response::make($this->ajaxResponse()->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->ajaxResponse()->getContentType() .
            ';charset="' . $this->ajaxResponse()->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
