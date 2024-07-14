<?php

namespace Jaxon\Laravel\App;

use Illuminate\Support\Facades\View as LaravelView;
use Jaxon\App\View\Store;
use Jaxon\App\View\ViewInterface;

use function trim;
use function view;

class View implements ViewInterface
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
    public function addNamespace(string $sNamespace, string $sDirectory, string $sExtension = '')
    {
        if(($sNamespace) && ($sDirectory))
        {
            LaravelView::addNamespace($sNamespace, $sDirectory);
            if(($sExtension) && $sExtension !== 'blade.php')
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
    public function render(Store $store): string
    {
        $sNamespace = $store->getNamespace();
        $sViewName = !$sNamespace || $sNamespace === 'blade' ?
            $store->getViewName() : $sNamespace . '::' . $store->getViewName();

        // Render the template
        return trim(view($sViewName, $store->getViewData()), " \t\n");
    }
}
