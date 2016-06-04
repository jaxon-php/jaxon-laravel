<?php

namespace Jaxon\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Jaxon extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jaxon';
    }
}
