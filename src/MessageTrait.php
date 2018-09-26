<?php

namespace ipl\Http;

trait MessageTrait
{
    /**
     * Case sensitive header names with lowercase header names as keys
     *
     * @var array
     */
    protected $headerNames = [];

    /**
     * Header values with lowercase header names as keys
     *
     * @var array
     */
    protected $headerValues = [];

    /**
     * The body of this request
     *
     * @var string
     */
    protected $body;

    /**
     * Protocol version
     *
     * @var string
     */
    protected $protocolVersion;

    public function getHeaders()
    {
        return array_combine($this->headerNames, $this->headerValues);
    }

    public function hasHeader($header)
    {
        return isset($this->headerValues[strtolower($header)]);
    }

    public function getHeader($header)
    {
        $header = strtolower($header);
        if (isset($this->headerValues[$header])) {
            return $this->headerValues[$header];
        }
    }

    protected function setHeaders(array $headers)
    {
        // Prepare header field names and header field values according to
        // https://tools.ietf.org/html/rfc7230#section-3.2.4
        $names = array_map('rtrim', array_keys($headers));
        $headers = array_map(function ($value) {
            return trim($value, " \t");
        }, $headers);

        $normalized = array_map('strtolower', $names);

        $this->headerNames = array_combine(
            $normalized,
            $names
        );

        $this->headerValues = array_combine(
            $normalized,
            $headers
        );
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }
}
