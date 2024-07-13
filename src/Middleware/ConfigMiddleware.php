<?php

/**
 * AjaxMiddleware.php
 *
 * Middleware to process Jaxon ajax request.
 *
 * @package jaxon-core
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2022 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Laravel\Middleware;

use Jaxon\Exception\RequestException;
use Jaxon\Laravel\Jaxon;
use Illuminate\Http\Request;
use Closure;
 
class ConfigMiddleware
{
    /**
     * @var Jaxon
     */
    private $jaxon;

    /**
     * The constructor
     *
     * @param Jaxon $jaxon
     */
    public function __construct(Jaxon $jaxon)
    {
        $this->jaxon = $jaxon;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws RequestException
     */
    public function handle(Request $request, Closure $next)
    {
        // Setup the Jaxon library
        $this->jaxon->setup();

        // Unable to find a plugin to process the request
        return $next($request);
    }
}
