<?php

namespace Jaxon\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;

class JaxonController extends Controller
{
    public function __construct()
    {
        // parent::__construct();
    }

    public function process()
    {
        // Process Jaxon request
        if(\LaravelJaxon::canProcessRequest())
        {
            \LaravelJaxon::processRequest();
        }
    }
}
