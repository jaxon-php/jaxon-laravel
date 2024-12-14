<?php

use Jaxon\Exception\SetupException;
use Jaxon\Script\Factory\ParameterFactory;
use Jaxon\Script\JqCall;
use Jaxon\Script\JsCall;
use Jaxon\Script\JxnCall;

/**
 * Get an instance of a registered PHP class.
 *
 * @param string $sClassName
 *
 * @return mixed
 * @throws SetupException
 */
function cl(string $sClassName)
{
    return Jaxon\cl($sClassName);
}

/**
 * Factory for ajax calls to a registered PHP class or function.
 *
 * @param string $sClassName
 *
 * @return JxnCall
 */
function rq(string $sClassName = ''): JxnCall
{
    return Jaxon\rq($sClassName);
}

/**
 * Get the factory for calls to a js object or function.
 *
 * @param string $sJsObject
 *
 * @return JsCall
 */
function js(string $sJsObject = ''): JsCall
{
    return Jaxon\js($sJsObject);
}

/**
 * Shortcut to get the factory for calls to a global js object or function.
 *
 * @return JsCall
 */
function jw(): JsCall
{
    return Jaxon\jw();
}

/**
 * Get the single instance of the parameter factory
 *
 * @return ParameterFactory
 */
function pm(): ParameterFactory
{
    return Jaxon\pm();
}

/**
 * Create a JQuery selector with a given path
 *
 * @param string $sPath    The jQuery selector path
 * @param mixed $xContext    A context associated to the selector
 *
 * @return JqCall
 */
function jq(string $sPath = '', $xContext = null): JqCall
{
    return Jaxon\jq($sPath, $xContext);
}
