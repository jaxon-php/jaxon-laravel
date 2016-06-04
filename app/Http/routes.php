<?php

/*
|--------------------------------------------------------------------------
| Route to Jaxon request processor
|--------------------------------------------------------------------------
|
| All Jaxon requests are sent through this route to the JaxonController class.
|
*/

Route::post(config('jaxon.app.route', 'jaxon'), array('as' => 'jaxon', 'uses' => 'JaxonController@process'));
    