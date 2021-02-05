<?php

namespace Jaxon\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Jaxon\Laravel\Jaxon;

class JaxonController extends Controller
{
    /**
     * Process a Jaxon request.
     *
     * The parameter is automatically populated by Laravel, thanks to its service container.
     *
     * @param Jaxon             $jaxon                  The Laravel Jaxon plugin
     *
     * @return The HTTP response to send back to the browser
     */
    public function process(Jaxon $jaxon)
    {
        $jaxon->callback()->before(function ($target, &$bEndRequest) {
            /*
            if($target->isFunction())
            {
                $function = $target->getFunctionName();
            }
            elseif($target->isClass())
            {
                $class = $target->getClassName();
                $method = $target->getMethodName();
                // $instance = $jaxon->instance($class);
            }
            */
        });
        $jaxon->callback()->after(function ($target, $bEndRequest) {
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
        if($jaxon->canProcessRequest())
        {
            return $jaxon->processRequest();
        }
    }
}
