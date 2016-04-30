<?php

/**
 * Return the javascript call to an Xajax controller method
 *
 * @param string|object $controller the controller
 * @param string $method the name of the method
 * @param array $parameters the parameters of the method
 * @return string
 */
function lxCall($controller, $method, array $parameters = array())
{
	return \App::make('xajax.request')->call($controller, $method, $parameters);
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
function lxPaginator($itemsTotal, $itemsPerPage, $currentPage, $controller, $method, array $parameters = array())
{
	return \App::make('xajax.request')->paginator($itemsTotal, $itemsPerPage, $currentPage, $controller, $method, $parameters);
}

/**
 * Get all the values in a form
 *
 * @param string $sFormId the id of the HTML form
 * @return array
 */
function lxForm($sFormId)
{
	return \App::make('xajax.request')->form($sFormId);
}

/**
 * Get the value of an input field
 *
 * @param string $sInputId the id of the HTML input element
 * @return array
 */
function lxInput($sInputId)
{
	return \App::make('xajax.request')->input($sInputId);
}

/**
 * Get the value of a checkbox field
 *
 * @param string $sInputId the name of the HTML checkbox element
 * @return array
 */
function lxCheckbox($sInputId)
{
	return \App::make('xajax.request')->checked($sInputId);
}

/**
 * Get the value of a select field
 *
 * @param string $sInputId the name of the HTML checkbox element
 * @return array
 */
function lxSelect($sInputId)
{
	return \App::make('xajax.request')->checked($sInputId);
}

/**
 * Get the value of a element in the DOM
 *
 * @param string $sElementId the id of the HTML element
 * @return array
 */
function lxHtml($sElementId)
{
	return \App::make('xajax.request')->html($sElementId);
}

/**
 * Return a string value
 *
 * @param string $sValue the value of the parameter
 * @return array
 */
function lxString($sValue)
{
	return \App::make('xajax.request')->string($sValue);
}

/**
 * Return a numeric value
 *
 * @param numeric $nValue the value of the parameter
 * @return array
 */
function lxNumeric($nValue)
{
	return \App::make('xajax.request')->numeric($nValue);
}

/**
 * Return an integer value
 *
 * @param numeric $nValue the value of the parameter
 * @return array
 */
function lxInteger($nValue)
{
	return \App::make('xajax.request')->integer($nValue);
}

/**
 * Return a javascript expression
 *
 * @param string $sValue the Js code of the parameter
 * @return array
 */
function lxJavascript($sValue)
{
	return \App::make('xajax.request')->javascript($sValue);
}

/**
 * Return a page number
 *
 * @return array
 */
function lxPageNumber()
{
	return \App::make('xajax.request')->pageNumber();
}
