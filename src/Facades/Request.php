<?php namespace \Xajax\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Request extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'xajax.request';
	}
}
