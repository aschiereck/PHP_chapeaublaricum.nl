<?php
namespace App\Util\Http;

use Zend\Http\Exception;
use Zend\Http\Headers;
use Zend\Http\Response;

interface IHttpClient
{
    /**
     * Set the HTTP method (to the request)
     *
     * @param string $method
     */
    public function setMethod($method);

    /**
     * Get the HTTP method
     * @return string
     */
    public function getMethod();

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout);

    /**
     * Set the POST parameters
     *
     * @param $post
     */
    public function setParameterPost($post);

    /**
     * Set the headers (for the request)
     *
     * @param  Headers|array $headers
     * @throws Exception\InvalidArgumentException
     */
    public function setHeaders($headers);

    /**
     * Send HTTP request
     *
     * @return Response
     * @throws Exception\RuntimeException
     */
    public function send();

    /**
     * @param string $username
     * @param string $password
     */
    public function setBasicAuthentication($username, $password);
}