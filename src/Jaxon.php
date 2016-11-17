<?php

namespace Jaxon\Laravel;

class Jaxon
{
    use \Jaxon\Framework\PluginTrait;

    /**
     * Initialise the Jaxon module.
     * 
     * This function has nothing to do.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = new View();
        // Jaxon library default options
        $this->jaxon->setOptions(array(
            'js.app.extern' => !config('app.debug', false),
            'js.app.minify' => !config('app.debug', false),
            'js.app.uri' => asset('jaxon/js'),
            'js.app.dir' => public_path('jaxon/js'),
        ));

        // Jaxon library user options
        $libConfig = config('jaxon.lib', array());
        $this->jaxon->setOptions($libConfig);

        // Jaxon application config
        $controllerDir = config('jaxon.app.dir', app_path('Jaxon/Controllers'));
        $namespace = config('jaxon.app.namespace', '\\Jaxon\\App');
        $excluded = config('jaxon.app.excluded', array());
        // The public methods of the Controller base class must not be exported to javascript
        $controllerClass = new \ReflectionClass('\\Jaxon\\Laravel\\Controller');
        foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
        {
            $excluded[] = $xMethod->getShortName();
        }

        // The request URI can be set with a Laravel route
        if(!$this->jaxon->hasOption('core.request.uri'))
        {
            if(($route = config('jaxon.app.route', null)))
                $this->jaxon->setOption('core.request.uri', route($route));
            else
                $this->jaxon->setOption('core.request.uri', 'jaxon');
        }
        // Register the default Jaxon class directory
        $this->jaxon->addClassDir($controllerDir, $namespace, $excluded);
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
        // Send HTTP Headers
        // $this->response->sendHeaders();
        // Create and return a Laravel HTTP response
        $httpResponse = \Response::make($this->response->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->response->getContentType() .
            ';charset="' . $this->response->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
