<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\TextResponse;
use Zend\Stratigility\MiddlewareInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        try
        {
            return $out($request, $response);
        }
        catch(\Exception $e)
        {
            return new TextResponse($e->getMessage(), 500);
        }
    }
}