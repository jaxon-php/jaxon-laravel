<?php

namespace Xajax\Laravel;

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
        return view()->make($template, $data);
    }

    /**
     * Set an Xajax presenter on a Laravel paginator
     *
     * @param object $paginator the Laravel paginator
     * @param integer $currentPage the current page
     * @param string|object $controller the controller
     * @param string $method the name of the method
     * @param array $parameters the parameters of the method

     * @return void
     */
    public static function setPresenter($paginator, $currentPage, $request)
    {
        // Append the page number to the parameter list, if not yet given.
        if(!$request->hasPageNumber())
        {
            $request->addParameter(XAJAX_PAGE_NUMBER, 0);
        }
        // Set the Laravel paginator to use our presenter 
        Paginator::presenter(function($paginator) use ($request, $currentPage)
        {
            return new Pagination\Presenter($paginator, $currentPage, $request);
        });
    }
}
