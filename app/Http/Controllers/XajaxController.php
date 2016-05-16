<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class XajaxController extends Controller
{
	public function __construct()
	{
		// parent::__construct();
	}

	public function process()
	{
		// Process Xajax request
		if(\LaravelXajax::canProcessRequest())
		{
			\LaravelXajax::processRequest();
		}
	}
}
