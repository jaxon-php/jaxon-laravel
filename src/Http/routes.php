<?php

/*
|--------------------------------------------------------------------------
| Route to Jaxon request processor
|--------------------------------------------------------------------------
|
| All Jaxon requests are sent through this route to the JaxonController class.
|
*/

$route = config('jaxon.app.route', 'jaxon');
if(!Route::has($route))
{
    Route::post($route, array(
        'as' => 'jaxon',
        'uses' => '\Jaxon\Laravel\Http\Controllers\JaxonController@process'
    ));
}
