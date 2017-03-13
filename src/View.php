<?php

namespace Jaxon\Laravel;

use Jaxon\Module\View\Store;
use Jaxon\Module\View\Facade;

class View extends Facade
{
    /**
     * Render a view
     * 
     * @param Store         $store        A store populated with the view data
     * 
     * @return string        The string representation of the view
     */
    public function make(Store $store)
    {
        // Render the template
        return trim(view($store->getViewPath(), $store->getViewData()), " \t\n");
    }
}
