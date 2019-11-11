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
     * @param Jaxon             $jaxon                  The Laravel Jaxon plugin
     */
    public function __construct(Jaxon $jaxon)
    {
        $this->jaxon = $jaxon;
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
        $this->jaxon->callback()->before(function ($target, &$bEndRequest) {
            /*
            if($target->isFunction())
            {
                $function = $target->getFunctionName();
            }
            elseif($target->isClass())
            {
                $class = $target->getClassName();
                $method = $target->getMethodName();
                // $instance = $this->jaxon->instance($class);
            }
            */
        });
        $this->jaxon->callback()->after(function ($target, $bEndRequest) {
            /*
            if($target->isFunction())
            {
                $function = $target->getFunctionName();
            }
            elseif($target->isClass())
            {
                $class = $target->getClassName();
                $method = $target->getMethodName();
            }
            */
        });

        // Process the Jaxon request
        if($this->jaxon->canProcessRequest())
        {
            return $this->jaxon->processRequest();
        }
    }
}
