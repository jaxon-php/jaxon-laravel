<?php

/*
|--------------------------------------------------------------------------
| Route to Jaxon request processor
|--------------------------------------------------------------------------
|
| All Jaxon requests are sent through this route to the JaxonController class.
|
*/

$routeName = config('jaxon.app.route', 'jaxon');
if(!Route::has($routeName))
{
    $route = Route::post($routeName, array(
        'as' => 'jaxon',
        'uses' => '\Jaxon\Laravel\Http\Controllers\JaxonController@process'
    ));
    // Starting from Laravel 5.2, the route must use the 'web' middleware.
    if(version_compare(app()->version(), '5.2.0', '>='))
    {
        $route->middleware('web');
    }
}
