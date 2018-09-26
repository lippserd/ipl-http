<?php

namespace ipl\Http;

interface MessageInterface
{
    /**
     * Get the HTTP protocol version
     *
     * @return  string  HTTP protocol version
     */
    public function getProtocolVersion();

    /**
     * Get all headers in terms of name-value pairs
     *
     * The case of the header field names is preserved in which headers were originally specified.
     *
     * @return  array   Header name-value pairs
     */
    public function getHeaders();

    /**
     * Get whether a header value exists for the given case-insensitive header name
     *
     * @param   string  $header Case-insensitive header field name
     *
     * @return  bool
     */
    public function hasHeader($header);

    /**
     * Get a header value by the the given case-insensitive header name
     *
     * @param   string  $header Case-insensitive header field name
     *
     * @return  string
     */
    public function getHeader($header);

    /**
     * Get the body of the message
     *
     * @return  string
     */
    public function getBody();
}
