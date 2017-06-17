<?php

namespace Jaxon\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelJaxon;

class JaxonController extends Controller
{
    /**
     * Process the Jaxon request
     * 
     * @return void
     */
    public function process()
    {
        // session()->start();
        // Process the Jaxon request
        if(LaravelJaxon::canProcessRequest())
        {
            LaravelJaxon::processRequest();
            return LaravelJaxon::httpResponse();
        }
    }
}
