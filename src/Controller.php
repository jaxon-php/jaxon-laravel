<?php

namespace Xajax\Laravel;

use App;

class Controller
{
    use \Xajax\Request\FactoryTrait;

    // Application data
    public $view = null;
    public $response = null;

    /**
     * Create a new Controller instance.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Initialise the controller.
     *
     * @return void
     */
    public function init()
    {}

    /**
     * Find an Xajax controller by name
     *
     * @param string $method the name of the method
     * @return object the Xajax controller, or null
     */
    public function controller($name)
    {
        // If the class name starts with a dot, then find the class in the same class path as the caller
        if(substr($name, 0, 1) == '.')
        {
            $name = $this->getXajaxClassPath() . $name;
        }
        // The controller namespace is prepended to the class name
        else if(($namespace = $this->getXajaxNamespace()))
        {
            $name = str_replace(array('\\'), array('.'), trim($namespace, '\\')) . '.' . $name;
        }
        return App::make('xajax')->controller($name);
    }
}
