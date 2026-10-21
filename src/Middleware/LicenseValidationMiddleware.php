<?php

namespace App\Middleware;

use App\Service\License\LicenseValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\TextResponse;
use Zend\Stratigility\MiddlewareInterface;

class LicenseValidationMiddleware implements MiddlewareInterface
{
    /**
     * @var LicenseValidator
     */
    private $validator;

    /**
     * LicenseValidationMiddleware constructor.
     * @param LicenseValidator $validator
     */
    public function __construct(LicenseValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $result = $this->validator->getLicense();

        if($result === false)
        {
            return new TextResponse('Invalid license', 503);
        }
        else
        {
            foreach($result as $key=>$value)
            {
                $request = $request->withAttribute($key, $value);
            }
        }

        return $out($request, $response);
    }
}