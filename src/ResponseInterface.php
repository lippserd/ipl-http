<?php

namespace ipl\Http;

/**
 * Representation of a HTTP response
 */
interface ResponseInterface extends MessageInterface
{
    /**
     * Get the response status code
     *
     * @return  int 3-digit status code
     */
    public function getStatusCode();
}
