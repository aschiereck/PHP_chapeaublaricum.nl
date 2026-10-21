<?php

namespace App\Middleware\Admin;

use App\Action\Admin\Dashboard\IndexAction;
use App\Exception\NotFoundException;
use App\Repository\ISiteRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Stratigility\MiddlewareInterface;

class SiteMiddleware implements MiddlewareInterface
{
    const SITE_ID = 'siteId';
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
        $siteId = $request->getAttribute(self::SITE_ID);

        if($siteId)
        {
            $site = $this->siteRepository->findById($siteId);
            if(!$site)
            {
                throw new NotFoundException();
            }

            $request = $request->withAttribute(self::SITE, $site);
        }
        else
        {
            $site = $this->siteRepository->findFirstSite();
            return new RedirectResponse('/' . ADMIN_SUFFIX . '/' . $site->getId() .  IndexAction::ROUTE);
        }

        return $out($request, $response);
    }
}
