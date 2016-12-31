<?php

namespace Jaxon\Laravel;

class Jaxon
{
    use \Jaxon\Module\Traits\Module;

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
        $jaxon->setOptions($libConfig);

        // Jaxon application settings
        $this->appConfig = new \Jaxon\Utils\Config();
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
        $this->setLibraryOptions(!$isDebug, !$isDebug, asset('jaxon/js'), public_path('jaxon/js'));

        // Jaxon application default settings
        $this->setApplicationOptions(app_path('Jaxon/Controllers'), '\\Jaxon\\App');

        // Jaxon controller class
        $this->setControllerClass('\\Jaxon\\Laravel\\Controller');
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
     * Return the view renderer.
     *
     * @return void
     */
    protected function jaxonView()
    {
        if($this->jaxonViewRenderer == null)
        {
            $this->jaxonViewRenderer = new View();
        }
        return $this->jaxonViewRenderer;
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
        $httpResponse = \Response::make($this->jaxonResponse->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->jaxonResponse->getContentType() .
            ';charset="' . $this->jaxonResponse->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
