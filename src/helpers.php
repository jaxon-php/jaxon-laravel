<?php

use Xajax\Request\Factory as xr;

/**
 * Return the javascript call to an Xajax controller method
 *
 * @param string|object $controller the controller
 * @param string $method the name of the method
 * @param ... $parameters the parameters of the method
 * @return object the Xajax request to the method
 */
function lxCall($controller, $method)
{
    if(is_string($controller))
    {
        $controller = \App::make('xajax')->controller($controller);
    }
    $aArgs = array_slice(func_get_args(), 1);
    // Make the request
    return call_user_func_array(array($controller, 'call'), $aArgs);
}

/**
 * Make the pagination for an Xajax controller method
 *
 *@param integer $itemsTotal the total number of items
 * @param integer $itemsPerPage the number of items per page page
 * @param integer $page the current page
 * @param string|object $controller the controller
 * @param string $method the name of the method
 * @param ... $parameters the parameters of the method
 * 
 * @return string the pagination links
 */
function lxPaginate($itemsTotal, $itemsPerPage, $currentPage, $controller, $method)
{
    if(is_string($controller))
    {
        $controller = \App::make('xajax')->controller($controller);
    }
    // Remove the controller from the args array
    $aArgs = func_get_args();
    array_splice($aArgs, 3, 1);
    // Make the pagination
    return call_user_func_array(array($controller, 'paginate'), $aArgs);
}

/**
 * Get all the values in a form
 *
 * @param string $sFormId the id of the HTML form
 * @return array
 */
function lxForm($sFormId)
{
    return xr::form($sFormId);
}

/**
 * Get the value of an input field
 *
 * @param string $sInputId the id of the HTML input element
 * @return array
 */
function lxInput($sInputId)
{
    return xr::input($sInputId);
}

/**
 * Get the value of a checkbox field
 *
 * @param string $sInputId the name of the HTML checkbox element
 * @return array
 */
function lxCheckbox($sInputId)
{
    return xr::checked($sInputId);
}

/**
 * Get the value of a select field
 *
 * @param string $sInputId the name of the HTML checkbox element
 * @return array
 */
function lxSelect($sInputId)
{
    return xr::checked($sInputId);
}

/**
 * Get the value of a element in the DOM
 *
 * @param string $sElementId the id of the HTML element
 * @return array
 */
function lxHtml($sElementId)
{
    return xr::html($sElementId);
}

/**
 * Return a string value
 *
 * @param string $sValue the value of the parameter
 * @return array
 */
function lxString($sValue)
{
    return xr::string($sValue);
}

/**
 * Return a numeric value
 *
 * @param numeric $nValue the value of the parameter
 * @return array
 */
function lxNumeric($nValue)
{
    return xr::numeric($nValue);
}

/**
 * Return an integer value
 *
 * @param numeric $nValue the value of the parameter
 * @return array
 */
function lxInteger($nValue)
{
    return xr::integer($nValue);
}

/**
 * Return a javascript expression
 *
 * @param string $sValue the Js code of the parameter
 * @return array
 */
function lxJavascript($sValue)
{
    return xr::javascript($sValue);
}

/**
 * Return a page number
 *
 * @return array
 */
function lxPageNumber()
{
    return xr::pageNumber();
}
