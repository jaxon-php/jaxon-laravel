<?php

namespace Jaxon\Laravel;

class Jaxon
{
    use \Jaxon\Framework\JaxonTrait;

    /**
     * Create a new Jaxon instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->jaxon = jaxon();
        $this->response = new Response();
        $this->view = new View();
    }

    /**
     * Initialise the Jaxon module.
     * 
     * This function has nothing to do.
     *
     * @return void
     */
    public function setup()
    {
        // Use the Composer autoloader
        $this->jaxon->useComposerAutoloader();
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
}
