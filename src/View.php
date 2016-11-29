<?php

namespace Jaxon\Laravel;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class View
{
    /**
     * Make a piece of data available for all views
     *
     * @param string        $name            The data name
     * @param string        $value            The data value
     * 
     * @return void
     */
    public function share($name, $value)
    {
        view()->share($name, $value);
    }

    /**
     * Render a template
     *
     * @param string        $template        The template path
     * @param string        $data            The template data
     * 
     * @return mixed        The rendered template
     */
    public function render($template, array $data = array())
    {
        return view($template, $data);
    }
}
