<?php

namespace Xajax\Laravel;

use Illuminate\Support\ServiceProvider;

class XajaxServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Config source and destination files
		$configSrcFile = __DIR__ . '/../../config/config.php';
		$configDstFile = config_path('xajax.php');
		// Publish assets and config
		$this->publishes([
			$configSrcFile => $configDstFile,
		]);
		
		// Define the helpers
		require_once (__DIR__ . '/helpers.php');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Register the Xajax singleton
		$this->app->singleton('xajax', function ($app)
		{
			// Xajax application config
			$requestRoute = config('xajax.app.route', 'xajax');
			$controllerDir = config('xajax.app.controllers', app_path() . '/Ajax/Controllers');
			$namespace = config('xajax.app.namespace', '\\App\\Xajax');
	
			$excluded = config('xajax.app.excluded', array());
			// The public methods of the Controller base class must not be exported to javascript
			$controllerClass = new \ReflectionClass('\\Xajax\\Laravel\\Controller');
			foreach ($controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $xMethod)
			{
				$excluded[] = $xMethod->getShortName();
			}

			$xajax = \Xajax\Xajax::getInstance();
			// Use the Composer autoloader
			$xajax->useComposerAutoLoader();
			// Xajax library default options
			$xajax->setOptions(array(
				'js.app.export' => !config('app.debug', false),
				'js.app.minify' => !config('app.debug', false),
				'js.app.uri' => asset('/xajax/js'),
				'js.app.dir' => public_path('/xajax/js'),
			));
			// Xajax library user options
			\Xajax\Config\Php::read(base_path('/config/xajax.php'), 'lib');
			// The request URI can be set with a Laravel route
			if(!$xajax->hasOption('core.request.uri'))
			{
				$xajax->setOption('core.request.uri', url($requestRoute));
			}
			// Register the default Xajax class directory
			$xajax->addClassDir($controllerDir, $namespace, $excluded);

			return new Xajax();
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array(
			'xajax'
		);
	}
}
