<?php

namespace ipl\Http;

/**
 * A HTTP response
 */
class Response implements ResponseInterface
{
    use MessageTrait;

    /**
     * Status code of this response
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Create a new HTTP response
     *
     * @param   int     $statusCode         Response status code
     * @param   array   $headers            Response headers
     * @param   string  $body               Response body
     * @param   string  $protocolVersion    Protocol version
     */
    public function __construct($statusCode = 200, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        $this->statusCode = $statusCode;
        $this->setHeaders($headers);
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
