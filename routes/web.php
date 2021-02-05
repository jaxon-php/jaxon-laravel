<?php

use Illuminate\Support\Facades\Route;
use Jaxon\Laravel\Http\Controllers\JaxonController;

/*
|--------------------------------------------------------------------------
| Route to Jaxon request processor
|--------------------------------------------------------------------------
|
| All Jaxon requests are sent through this route to the JaxonController class.
|
*/

$routePath = config('jaxon.lib.core.request.uri', '/jaxon');
$routeName = config('jaxon.app.request.route', 'jaxon');

if(!Route::has($routeName))
{
    Route::post($routePath, JaxonController::class . '@process')
        ->name($routeName)
        ->middleware('web');
}
