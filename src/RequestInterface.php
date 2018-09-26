<?php

namespace ipl\Http;

/**
 * Representation of a HTTP request
 */
interface RequestInterface extends MessageInterface
{
    /**
     * Get the URL of the request
     *
     * @return  string  The request URL
     */
    public function getUrl();

    /**
     * Get the HTTP method of the request
     *
     * @return  string  Request method
     */
    public function getMethod();
}



