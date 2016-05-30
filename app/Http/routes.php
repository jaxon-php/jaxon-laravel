<?php

/*
|--------------------------------------------------------------------------
| Route to Xajax request processor
|--------------------------------------------------------------------------
|
| All Xajax requests are sent through this route to the XajaxController class.
|
*/

Route::post(config('xajax.app.route', 'xajax'), array('as' => 'xajax', 'uses' => 'XajaxController@process'));
    