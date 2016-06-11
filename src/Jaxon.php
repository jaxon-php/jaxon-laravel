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
        $this->jaxon = \Jaxon\Jaxon::getInstance();
        $this->validator = \Jaxon\Utils\Container::getInstance()->getValidator();
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
    }
}
