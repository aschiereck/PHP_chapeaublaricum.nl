<?php

namespace App\Middleware\Frontend;

use App\Exception\NotFoundException;
use App\Repository\ISiteRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class SiteMiddleware implements MiddlewareInterface
{
    const SITE = 'site';

    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * SiteMiddleware constructor.
     * @param ISiteRepository $siteRepository
     */
    public function __construct(ISiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     * @throws NotFoundException
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $host = $request->getUri()->getHost();
        $site = $this->siteRepository->finByUrl($host);

        if(!$site)
        {
            throw new NotFoundException();
        }

        $request = $request->withAttribute(self::SITE, $site);

        return $out($request, $response);
    }
}
