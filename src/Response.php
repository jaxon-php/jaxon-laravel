<?php

namespace Xajax\Laravel;

class Response extends \Xajax\Response\Response
{
    /**
     * Create a new Response instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Wrap the Xajax response in a Laravel HTTP response.
     *
     * @param  string  $code
     * @return string  the HTTP response
     */
    public function http($code = '200')
    {
        // Create and return a Laravel HTTP response
        $httpResponse = \Response::make($this->getOutput(), $code);
        $httpResponse->header('Content-Type', $this->getContentType() . ';charset="' . $this->getCharacterEncoding() . '"');
        return $httpResponse;
    }
}
