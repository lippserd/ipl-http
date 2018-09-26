<?php

namespace ipl\Http;

/**
 * A HTTP request
 */
class Request implements RequestInterface
{
    use MessageTrait;

    /**
     * URL of the request
     *
     * @var string
     */
    protected $url;

    /**
     * HTTP method of the request
     *
     * @var string
     */
    protected $method;

    /**
     * Create a new HTTP request
     *
     * @param   string      $method             HTTP method
     * @param   string      $url                URL
     * @param   array       $headers            Request headers
     * @param   string      $body               Request body
     * @param   string      $protocolVersion    Protocol version
     */
    public function __construct($method, $url, array $headers = [], $body = null, $protocolVersion = '1.1')
    {
        $this->method = $method;
        $this->url = $url;
        $this->setHeaders($headers);
        $this->body = $body;
        $this->protocolVersion = $protocolVersion;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
