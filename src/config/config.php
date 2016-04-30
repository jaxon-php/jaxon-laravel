<?php

return array(
	'app' => array(
		// 'route' => '',
		// 'namespace' => '',
		// 'controllers' => '',
		// 'extensions' => '',
		// 'excluded' => [],
	),
	'lib' => array(
		'core' => array(
			'language' => 'en',
			'encoding' => 'UTF-8',
			'prefix' => array(
				'class' => 'Xajax',
				'function' => 'xajax_',
			),
			'js' => array(
				// 'lib_uri' => '',
				// 'lib' => '',
				// 'dir' => '',
				'merge' => true,
				'minify' => true,
				'dir' => 'deferred',
			),
			'debug' => array(
				'on' => false,
				'verbose' => false,
			),
			'error' => array(
				'handle' => false,
			),
		),
	),
);
