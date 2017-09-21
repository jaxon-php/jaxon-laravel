<?php

namespace Jaxon\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Jaxon\Laravel\Jaxon;

class JaxonController extends Controller
{
    /**
     * @var Jaxon           The Laravel Jaxon service
     */
    protected $jaxon;

    /**
     * The constructor.
     * 
     * The parameter is automatically populated by Laravel, thanks to its service container.
     * 
     * @param Jaxon             $jaxon                  The Laravel Jaxon plugin
     */
    public function __construct(Jaxon $jaxon)
    {
        $this->jaxon = $jaxon;
    }

    /**
     * Callback for initializing a Jaxon class instance.
     * 
     * This function is called anytime a Jaxon class is instanciated.
     *
     * @param object            $instance               The Jaxon class instance
     *
     * @return void
     */
    public function initInstance($instance)
    {
    }

    /**
     * Callback before processing a Jaxon request.
     *
     * @param object            $instance               The Jaxon class instance to call
     * @param string            $method                 The Jaxon class method to call
     * @param boolean           $bEndRequest            Whether to end the request or not
     *
     * @return void
     */
    public function beforeRequest($instance, $method, &$bEndRequest)
    {
    }

    /**
     * Callback after processing a Jaxon request.
     *
     * @param object            $instance               The Jaxon class instance called
     * @param string            $method                 The Jaxon class method called
     *
     * @return void
     */
    public function afterRequest($instance, $method)
    {
    }

    /**
     * Process a Jaxon request.
     * 
     * The parameter is automatically populated by Laravel, thanks to its service container.
     * 
     * @param Jaxon             $this->jaxon                  The Laravel Jaxon plugin
     *
     * @return The HTTP response to send back to the browser
     */
    public function process()
    {
        $this->jaxon->onInit(function($instance){
            $this->initInstance($instance);
        });
        $this->jaxon->onBefore(function($instance, $method, &$bEndRequest){
            $this->beforeRequest($instance, $method, $bEndRequest);
        });
        $this->jaxon->onAfter(function($instance, $method){
            $this->afterRequest($instance, $method);
        });

        // Process the Jaxon request
        if($this->jaxon->canProcessRequest())
        {
            $this->jaxon->processRequest();
            return $this->jaxon->httpResponse();
        }
    }
}
