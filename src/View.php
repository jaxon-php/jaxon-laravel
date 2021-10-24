<?php

namespace Jaxon\Laravel;

use Illuminate\Support\Facades\View as LaravelView;

use Jaxon\Utils\View\Store;
use Jaxon\Contracts\View as ViewContract;

class View implements ViewContract
{
    /**
     * Add a namespace to this view renderer
     *
     * @param string        $sNamespace         The namespace name
     * @param string        $sDirectory         The namespace directory
     * @param string        $sExtension         The extension to append to template names
     *
     * @return void
     */
    public function addNamespace($sNamespace, $sDirectory, $sExtension = '')
    {
        if(($sNamespace) && ($sDirectory))
        {
            LaravelView::addNamespace($sNamespace, $sDirectory);
            if(($sExtension) && $sExtension != 'blade.php')
            {
                LaravelView::addExtension($sExtension, 'blade');
            }
        }
    }

    /**
     * Render a view
     *
     * @param Store         $store        A store populated with the view data
     *
     * @return string        The string representation of the view
     */
    public function render(Store $store)
    {
        // Render the template
        return trim(view($store->getViewName(), $store->getViewData()), " \t\n");
    }
}
