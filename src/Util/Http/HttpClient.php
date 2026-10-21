<?php

namespace App\Util\Http;

use Zend\Http\Exception;
use Zend\Http\Headers;
use Zend\Http\Response;

class HttpClient implements IHttpClient
{
    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var array
     */
    private $postParameter = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var int
     */
    private $timeout = 30;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $proxyHost;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;


    /**
     * HttpClientFactory constructor.
     * @param string $proxy
     * @param string $url
     */
    public function __construct($proxy, $url)
    {
        $this->url = $url;
        $this->proxyHost = $proxy;
    }

    /**
     * Set the HTTP method (to the request)
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get the HTTP method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the POST parameters
     *
     * @param $post
     */
    public function setParameterPost($post)
    {
        $this->postParameter = $post;
    }

    /**
     * Set the headers (for the request)
     *
     * @param  Headers|array $headers
     * @throws Exception\InvalidArgumentException
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function setBasicAuthentication($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Send HTTP request
     *
     * @return Response
     * @throws Exception\RuntimeException
     */
    public function send()
    {
        $c = curl_init($this->url);
        curl_setopt($c, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($c, CURLOPT_DNS_CACHE_TIMEOUT, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        if($this->username && $this->password)
        {
            curl_setopt($c, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        }
        if($this->proxyHost)
        {
            curl_setopt($c, CURLOPT_PROXY, $this->proxyHost);
        }

        $headers = [];
        foreach($this->headers as $k=>$v)
        {
            $headers[] = $k . ':' . $v;
        }
        curl_setopt($c, CURLOPT_HTTPHEADER, $headers);

        if($this->method == 'POST')
        {
            if(is_array($this->postParameter))
            {
                curl_setopt($c,CURLOPT_POST, count($this->postParameter));
                curl_setopt($c,CURLOPT_POSTFIELDS, http_build_query($this->postParameter));
            }
            else
            {
                curl_setopt($c,CURLOPT_POSTFIELDS, $this->postParameter);
            }
        }

        $rawResponse = curl_exec($c);
        $httpResponseCode = curl_getinfo($c, CURLINFO_HTTP_CODE);

        $response = new Response();
        $response->setContent($rawResponse);
        $response->setStatusCode($httpResponseCode);

        return $response;
    }
}