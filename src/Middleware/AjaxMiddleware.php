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
use Jaxon\Laravel\App\Jaxon;
use Illuminate\Http\Request;
use Closure;

class AjaxMiddleware
{
    /**
     * The constructor
     *
     * @param Jaxon $jaxon
     */
    public function __construct(private Jaxon $jaxon)
    {}

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
        if(!$this->jaxon->canProcessRequest())
        {
            // Unable to find a plugin to process the request
            return $next($request);
        }

        // Process the Jaxon request
        return $this->jaxon->processRequest();
    }
}
