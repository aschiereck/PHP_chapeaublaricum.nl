<?php

namespace App\Session;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;


class StartSessionMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        //Start session
        session_name(AREA);
        session_start();

        return $out($request, $response);
    }
}