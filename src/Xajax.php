<?php namespace \Xajax\Laravel;

class Xajax
{
	protected $xajax = null;
	protected $request = null;
	protected $response = null;

	protected $preCallback = null;
	protected $postCallback = null;
	protected $initCallback = null;

	// Requested controller and method
	private $controller = null;
	private $method = null;

	/**
	 * Create a new Xajax instance.
	 *
	 * @return void
	 */
	public function __construct($controllers)
	{
		$this->xajax = \Xajax\Xajax::getInstance();
		$this->response = new Response();
	}

	/**
	 * Check if the current request is an Xajax request.
	 *
	 * @return boolean  True if the request is Xajax, false otherwise.
	 */
	public function hasRequest()
	{
		return $this->xajax->canProcessRequest();
	}

	/**
	 * Get the Xajax request.
	 *
	 * @return object  the Xajax request
	 */
	public function request()
	{
		if(!$this->request)
		{
			$this->request = \App::make('lajax.request');
		}
		return $this->request;
	}

	/**
	 * Get the Xajax response.
	 *
	 * @return object  the Xajax response
	 */
	public function response()
	{
		return $this->response;
	}

	/**
	 * Get the javascript code generated for all registered classes.
	 *
	 * @return string  the javascript code
	 */
	public function javascript()
	{
		return $this->xajax->getJavascript();
	}

	/**
	 * Get the javascript code generated for all registered classes.
	 *
	 * @return string  the javascript code
	 */
	public function js()
	{
		return $this->xajax->getJavascript();
	}

	/**
	 * Set the init callback, used to initialise controllers.
	 *
	 * @param  callable  $callable the callback function
	 * @return void
	 */
	public function setInitCallback($callable)
	{
		$this->initCallback = $callable;
	}

	/**
	 * Set the pre-request processing callback.
	 *
	 * @param  callable  $callable the callback function
	 * @return void
	 */
	public function setPreCallback($callable)
	{
		$this->preCallback = $callable;
	}

	/**
	 * Set the post-request processing callback.
	 *
	 * @param  callable  $callable the callback function
	 * @return void
	 */
	public function setPostCallback($callable)
	{
		$this->postCallback = $callable;
	}

	/**
	 * Initialise a controller.
	 *
	 * @return void
	 */
	protected function initController($controller)
	{
		// Si le controller a déjà été initialisé, ne rien faire
		if(!($controller) || ($controller->response))
		{
			return;
		}
		// Placer les données dans le controleur
		$controller->request = $this->request();
		$controller->response = $this->response;
		if(($this->initCallback))
		{
			$cb = $this->initCallback;
			$cb($controller);
		}
		$controller->init();
	}

	/**
	 * Get a controller instance.
	 *
	 * @param  string  $classname the controller class name
	 * @return object  an instance of the controller
	 */
	public function controller($classname)
	{
		$xajaxPluginManager = \Xajax\Plugin\Manager::getInstance();
		$xajaxCallableObjectPlugin = $xajaxPluginManager->getRequestPlugin('CallableObject');
		if(!$xajaxCallableObjectPlugin || !$xajaxPluginManager->registerClass($classname))
		{
			return null;
		}
		$controller = $xajaxCallableObjectPlugin->getRegisteredObject($classname);
		$this->initController($controller);
		return $controller;
	}

	/**
	 * This is the pre-request processing callback passed to the Xajax library.
	 *
	 * @param  boolean  &$bEndRequest if set to true, the request processing is interrupted.
	 * @return object  the Xajax response
	 */
	public function preProcess(&$bEndRequest)
	{
		// Instanciate the called class
		$class = $_POST['xjxcls'];
		$method = $_POST['xjxmthd'];
		// Todo : check and sanitize $class and $method inputs
		// Instanciate the controller. This will include the required file.
		$this->controller = $this->controller($class);
		$this->method = $method;
		if(!$this->controller)
		{
			// End the request processing if a controller cannot be found.
			$bEndRequest = true;
			return $this->response;
		}

		// Call the user defined callback
		if(($this->preCallback))
		{
			$cb = $this->preCallback;
			$cb($this->controller, $method, $bEndRequest);
		}
		return $this->response;
	}

	/**
	 * This is the post-request processing callback passed to the Xajax library.
	 *
	 * @return object  the Xajax response
	 */
	public function postProcess()
	{
		if(($this->postCallback))
		{
			$cb = $this->postCallback;
			$cb($this->controller, $this->method);
		}
		return $this->response;
	}

	/**
	 * Process the current Xajax request.
	 *
	 * @return void
	 */
	public function processRequest()
	{
		// Process Xajax Request
		$this->xajax->register(XAJAX_PROCESSING_EVENT, XAJAX_PROCESSING_EVENT_BEFORE, array($this, 'preProcess'));
		$this->xajax->register(XAJAX_PROCESSING_EVENT, XAJAX_PROCESSING_EVENT_AFTER, array($this, 'postProcess'));
		if($this->xajax->canProcessRequest())
		{
			// Traiter la requete
			$this->xajax->processRequest();
		}
	}

	/**
	 * Return the javascript call to an Xajax controller method
	 *
	 * @param string|object $controller the controller
	 * @param string $method the name of the method
	 * @param array $parameters the parameters of the method
	 * @return string
	 */
	public function call($controller, $method, array $parameters = array())
	{
		return $this->request()->call($controller, $method, $parameters);
	}

	/**
	 * Set an Xajax presenter on a Laravel paginator
	 *
	 * @param object $paginator the Laravel paginator
	 * @param string|object $controller the controller
	 * @param string $method the name of the method
	 * @param array $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	public function setPresenter($paginator, $controller, $method, array $parameters = array())
	{
		return $this->request()->setPresenter($paginator, $controller, $method, $parameters);
	}

	/**
	 * Make the pagination for an Xajax controller method
	 *
	 *@param integer $itemsTotal the total number of items
	 * @param integer $itemsPerPage the number of items per page page
	 * @param integer $page the current page
	 * @param string|object $controller the controller
	 * @param string $method the name of the method
	 * @param array $parameters the parameters of the method
	 * @return object the Laravel paginator instance
	 */
	public function paginator($itemsTotal, $itemsPerPage, $page, $controller, $method, array $parameters = array())
	{
		return $this->request()->paginator($itemsTotal, $itemsPerPage, $page, $controller, $method, $parameters);
	}
}

?>
