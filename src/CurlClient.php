<?php

namespace ipl\Http;

/**
 * HTTP client that uses cURL
 */
class CurlClient implements ClientInterface
{
    /**
     * Additional cURL options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Temporary storage for response headers
     *
     * @var array
     */
    protected $responseHeaders;

    /**
     * Return user agent
     *
     * @return  string
     */
    protected function getAgent()
    {
        $curlVersion = curl_version();

        $defaultAgent = 'ipl/' . static::VERSION;
        $defaultAgent .= ' curl/' . $curlVersion['version'];

        return $defaultAgent;
    }

    /**
     * Set additional cURL options
     *
     * @param   array   $options
     *
     * @return  $this
     */
    public function setCurlOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Return additional cURL options
     *
     * @return array
     */
    public function getCurlOptions()
    {
        return $this->options;
    }

    /**
     * Prepare and return a cURL handle based on the given request and clients' cURL options
     *
     * @param   RequestInterface    $request
     *
     * @return  resource
     *
     * @throws  HttpException
     */
    public function prepareHandle(RequestInterface $request)
    {
        $ch = curl_init($request->getUrl());

        // Bypass Expect: 100-continue timeouts
        $headers = [];
        foreach ($request->getHeaders() as $key => $value) {
            if (strtolower($key) === 'expect') {
                continue;
            }
            $headers[] = $key . ': ' . $value;
        }

        $constantOptions = [
            CURLOPT_USERAGENT       => $this->getAgent(),
            CURLOPT_RETURNTRANSFER  => 1
        ];

        $options = [
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_CUSTOMREQUEST   => $request->getMethod(),
            CURLOPT_FOLLOWLOCATION  => 1,
        ];

        if ($request->getProtocolVersion()) {
            $protocolVersion = null;
            switch ($request->getProtocolVersion()) {
                case '2.0':
                    if (version_compare(phpversion(), '7.0.7', '<')) {
                        throw new HttpException('You need at least PHP 7.0.7 to use HTTP 2.0');
                    }
                    $protocolVersion = CURL_HTTP_VERSION_2;
                    break;
                case '1.1':
                    $protocolVersion = CURL_HTTP_VERSION_1_1;
                    break;
                default:
                    $protocolVersion = CURL_HTTP_VERSION_1_0;
            }

            $options[CURLOPT_HTTP_VERSION] = $protocolVersion;
        }

        if ($request->getBody()) {
            $options[CURLOPT_POSTFIELDS] = $request->getBody();
        }

        $constantOptions[CURLOPT_HEADERFUNCTION] = function($_, $header) {
            $size = strlen($header);

            if (! trim($header) || strpos($header, 'HTTP/') === 0) {
                return $size;
            }

            list($key, $value) = $this->parseHeaderLine($header);
            $this->responseHeaders[$key] = rtrim($value, "\r\n");

            return $size;
        };

        $options = array_replace($options, $this->getCurlOptions());
        $options = array_replace($options, $constantOptions);

        curl_setopt_array($ch, $options);

        return $ch;
    }

    /**
     * Split header line into a key and value pair
     *
     * @param   string    $header
     *
     * @return  array
     */
    protected function parseHeaderLine($header)
    {
        return explode(':', $header, 2);
    }

    /**
     * Execute a cURL handle and return the response as response object
     *
     * @param   resource    $ch
     *
     * @return  ResponseInterface
     *
     * @throws  HttpException
     */
    public function executeHandle($ch)
    {
        $this->responseHeaders = [];

        $body = curl_exec($ch);

        if ($body === false) {
            throw new HttpException(curl_error($ch));
        }

        $response = new Response(curl_getinfo($ch, CURLINFO_HTTP_CODE), $this->responseHeaders, $body);

        curl_close($ch);

        return $response;
    }

    public function send(RequestInterface $request)
    {
        $ch = $this->prepareHandle($request);

        $response = $this->executeHandle($ch);

        return $response;
    }
}
