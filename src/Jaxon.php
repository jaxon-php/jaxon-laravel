<?php

namespace Jaxon\Laravel;

class Jaxon
{
    use \Jaxon\Module\Traits\Module;

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
     * Set the module specific options for the Jaxon library.
     *
     * @return void
     */
    protected function setup()
    {
        // Load Jaxon config settings
        $libConfig = config('jaxon.lib', array());
        $appConfig = config('jaxon.app', array());

        // Jaxon library settings
        $jaxon = jaxon();
        $jaxon->setOptions($libConfig);
        // Default values
        if(!$jaxon->hasOption('js.app.extern'))
        {
            $jaxon->setOption('js.app.extern', !config('app.debug', false));
        }
        if(!$jaxon->hasOption('js.app.minify'))
        {
            $jaxon->setOption('js.app.minify', !config('app.debug', false));
        }
        if(!$jaxon->hasOption('js.app.uri'))
        {
            $jaxon->setOption('js.app.uri', asset('jaxon/js'));
        }
        if(!$jaxon->hasOption('js.app.dir'))
        {
            $jaxon->setOption('js.app.dir', public_path('jaxon/js'));
        }
        // The request URI can be set with a Laravel route
        if(!$jaxon->hasOption('core.request.uri'))
        {
            if(($route = config('jaxon.app.route', null)))
            {
                $this->jaxon->setOption('core.request.uri', route($route));
            }
        }

        // Jaxon application settings
        $this->appConfig = new \Jaxon\Utils\Config();
        $this->appConfig->setOptions($appConfig);
        // Default values
        if(!$this->appConfig->hasOption('controllers.directory'))
        {
            $this->appConfig->setOption('controllers.directory', app_path('Jaxon/Controllers'));
        }
        if(!$this->appConfig->hasOption('controllers.namespace'))
        {
            $this->appConfig->setOption('controllers.namespace', '\\Jaxon\\App');
        }
        if(!$this->appConfig->hasOption('controllers.protected') || !is_array($this->appConfig->getOption('protected')))
        {
            $this->appConfig->setOption('controllers.protected', array());
        }
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
    protected function check()
    {
        // Todo: check the mandatory options
    }

    /**
     * Return the view renderer.
     *
     * @return void
     */
    protected function view()
    {
        if($this->viewRenderer == null)
        {
            $this->viewRenderer = new View();
        }
        return $this->viewRenderer;
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
        $httpResponse = \Response::make($this->response->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->response->getContentType() .
            ';charset="' . $this->response->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
