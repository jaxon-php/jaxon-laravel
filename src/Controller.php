<?php

namespace Xajax\Laravel;

use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App;

class Controller
{
	use \Xajax\Request\FactoryTrait;

	// Application data
	public $request = null;
	public $response = null;

	/**
	 * Create a new Controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{}

	/**
	 * Initialise the controller.
	 *
	 * @return void
	 */
	public function init()
	{}

	/**
	 * Make a request to a method of this controller
	 *
	 * @param string $method the name of the method
	 * @param ... $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	final public function call($method)
	{
		// This function is an alias to the request method of the Xajax Request Factory
		return call_user_func_array(array($this, 'request'), func_get_args());
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
	 * Make the pagination for an Xajax controller method
	 *
	 * @param integer $itemsTotal the total number of items
	 * @param integer $itemsPerPage the number of items per page page
	 * @param integer $currentPage the current page
	 * @param string $method the name of the method
	 * @param ... $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	final public function paginator($itemsTotal, $itemsPerPage, $currentPage, $method)
	{
		if($method instanceof \Xajax\Request\Request)
		{
			$request = $method;
		}
		else
		{
			$aArgs = array_slice(func_get_args(), 3);
			// Make the request
			$request = call_user_func_array(array($this, 'request'), $aArgs);
		}
		$paginator = new Paginator(array(), $itemsTotal, $itemsPerPage, $currentPage);
		return $this->setPresenter($paginator, $currentPage, $request);
	}

	/**
	 * Find an Xajax controller by name
	 *
	 * @param string $method the name of the method
	 * @return object the Xajax controller, or null
	 */
	public function controller($name)
	{
		// If the class name starts with a dot, then find the class in the same class path as the caller
		if(substr($name, 0, 1) == '.')
		{
			$name = $this->getXajaxClassPath() . $name;
		}
		// The configured namespace is prepended to the class name
		else if(($namespace = $this->getXajaxNamespace()))
		{
			$name = str_replace(array('\\'), array('.'), trim($namespace, '\\')) . '.' . $name;
		}
		return App::make('xajax')->controller($name);
	}
}
