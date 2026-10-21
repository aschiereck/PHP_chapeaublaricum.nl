<?php

namespace App\Action\Evolved;

use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class PingAction implements MiddlewareInterface
{
    const ROUTE = '/beheer/evolved/ping';

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        return new JsonResponse('pong', 200);
    }
}