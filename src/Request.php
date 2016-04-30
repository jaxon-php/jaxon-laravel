<?php

namespace \Xajax\Laravel;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class Request
{
	protected $xajax = null;

	/**
	 * Create a new Request instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->xajax = \App::make('xajax');
	}

	/**
	 * Save the parameters in the Xajax request object
	 *
	 * @param object $xajaxRequest the Xajax request
	 * @param array $parameters the parameters of the request
	 * @return string
	 */
	private function setParameters(&$xajaxRequest, array $parameters)
	{
		$xajaxRequest->clearParameters();
		$xajaxRequest->useSingleQuote();
		foreach($parameters as $param)
		{
			if(is_numeric($param))
			{
				$xajaxRequest->addParameter(XAJAX_NUMERIC_VALUE, $param);
			}
			else if(is_string($param))
			{
				$xajaxRequest->addParameter(XAJAX_QUOTED_VALUE, $param);
			}
			else if(is_array($param))
			{
				$xajaxRequest->addParameter($param[0], $param[1]);
			}
		}
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
	public function setPresenter($paginator, $currentPage, $controller, $method, array $parameters = array())
	{
		if(is_string($controller))
		{
			$controller = $this->xajax->controller($controller);
		}
		if(!is_object($controller))
		{
			return null;
		}
		// The Xajax library turns the method names into lower case chars.
		$method = strtolower($method);
		// Check if the xajax method exists
		if(!array_key_exists($method, $controller->requests))
		{
			return null;
		}
		// Since multiple requests can be created with different sets of parameters, they have to be cloned.
		$request = clone $controller->requests[$method];
		$this->setParameters($request, $parameters);
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
	 * Make the pagination for an Xajax controller method
	 *
	 * @param integer $itemsTotal the total number of items
	 * @param integer $itemsPerPage the number of items per page page
	 * @param integer $currentPage the current page
	 * @param string|object $controller the controller
	 * @param string $method the name of the method
	 * @param array $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	public function paginator($itemsTotal, $itemsPerPage, $currentPage, $controller, $method, array $parameters = array())
	{
		$paginator = new Paginator(array(), $itemsTotal, $itemsPerPage, $currentPage);
		return $this->setPresenter($paginator, $currentPage, $controller, $method, $parameters);
	}
}
