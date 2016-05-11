<?php

namespace Xajax\Laravel;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App;

class View
{
	protected $controller = null;

	public function __construct($controller)
	{
		$this->controller = $controller;
	}

	/**
	 * Set an Xajax presenter on a Laravel paginator
	 *
	 * @param object $paginator the Laravel paginator
	 * @param integer $currentPage the current page
	 * @param string|object $controller the controller
	 * @param string $method the name of the method
	 * @param array $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	public function setPresenter($paginator, $currentPage, $request)
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
		return $paginator;
	}

	/**
	 * Make the pagination for an Xajax controller method, and share the paginator in the view
	 *
	 * @param integer $itemsTotal the total number of items
	 * @param integer $itemsPerPage the number of items per page page
	 * @param integer $currentPage the current page
	 * @param string $method the name of the method
	 * @param ... $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	public function paginate($itemsTotal, $itemsPerPage, $currentPage, $method)
	{
		if($method instanceof \Xajax\Request\Request)
		{
			$request = $method;
		}
		else
		{
			$aArgs = array_slice(func_get_args(), 3);
			// Make the request
			$request = call_user_func_array(array($this->controller, 'request'), $aArgs);
		}
		$paginator = new Paginator(array(), $itemsTotal, $itemsPerPage, $currentPage);
		$this->setPresenter($paginator, $currentPage, $request);
		view()->share('paginator', $paginator);
		return $paginator;
	}
}
