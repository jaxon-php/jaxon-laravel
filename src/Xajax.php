<?php

namespace Xajax\Laravel;

use Xajax\Plugin\Manager as PluginManager;

class Xajax
{
	protected $xajax = null;
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
	public function __construct()
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
	 * Get the Xajax response.
	 *
	 * @return object  the Xajax response
	 */
	public function response()
	{
		return $this->response;
	}

	/**
	 * Register the Xajax classes.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->xajax->registerClasses();
	}

	/**
	 * Get the javascript code generated for all registered classes.
	 *
	 * @return string  the javascript code
	 */
	public function javascript()
	{
		return $this->xajax->getJavascript(false);
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
		$xajaxPluginManager = PluginManager::getInstance();
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
}

?>
