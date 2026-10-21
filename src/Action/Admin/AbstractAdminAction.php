<?php

namespace App\Action\Admin;

use App\Middleware\Admin\SiteMiddleware;
use App\Model\Link\AbstractLink;
use App\Model\Link\ExternalLink;
use App\Model\Link\PageLink;
use App\Model\Site;
use App\Repository\IPageRepository;
use App\Session\IFlashMessageHandler;
use App\View\Admin\Helper\AdminUrlHelper;
use App\View\Admin\Model\ViewModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractAdminAction implements MiddlewareInterface
{
    /** @var  Request */
    private $_request;


    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $this->_request = $request;
        return $this->execute();
    }

    /**
     * @return Response|array
     */
    protected abstract function execute();


    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    protected function getQueryParameter($name, $defaultValue = null)
    {
        $params = $this->_request->getQueryParams();
        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }

    /**
     * @param $name
     * @param null $defaultValue
     * @return mixed|null
     */
    protected function getPostParameter($name, $defaultValue=null)
    {
        $params = $this->_request->getParsedBody();
        return is_array($params) && isset($params[$name]) ? $params[$name] : $defaultValue;
    }

    /**
     * @param $name
     * @param bool $useInterface
     * @return mixed|null
     */
    protected function getFilesParameter($name, $useInterface=false)
    {
        if($useInterface)
        {
            $params = $this->_request->getUploadedFiles();
        }
        else
        {
            $params = $_FILES;
        }
        return is_array($params) && isset($params[$name]) ? $params[$name] : [];
    }

    /**
     * @return Site
     */
    protected function getSite() : Site
    {
        return $this->_request->getAttribute(SiteMiddleware::SITE);
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function getRouteParameter($name)
    {
        return $this->_request->getAttribute($name);
    }

    /**
     * @return IFlashMessageHandler
     */
    protected function getFlash()
    {
        return $this->getViewModel()->flashMessageHandler;
    }

    /**
     * @return ViewModel
     */
    protected function getViewModel()
    {
        return $this->_request->getAttribute(ViewModel::class);
    }

    /**
     * @return bool
     */
    protected function isPostRequest()
    {
        return $this->_request->getMethod() == 'POST';
    }

    /**
     * @return TemplateRendererInterface
     */
    protected function getTemplateRenderer()
    {
        return $this->_request->getAttribute(TemplateRendererInterface::class);
    }

    /**
     * @param $routeName
     * @param array $routeParams
     * @return RedirectResponse
     */
    protected function redirectTo($routeName, $routeParams= [])
    {
        $url = $this->buildUrl($routeName, $routeParams);
        return new RedirectResponse($url);
    }

    /**
     * @param $routeName
     * @param array $routeParams
     * @param bool $absolute
     * @return mixed
     */
    protected function buildUrl($routeName, $routeParams= [], $absolute=false)
    {
        if(!isset($routeParams['siteId']))
        {
            $routeParams['siteId'] = $this->_request->getAttribute(SiteMiddleware::SITE_ID);
        }

        /** @var AdminUrlHelper $helper */
        $helper = $this->_request->getAttribute(AdminUrlHelper::class);
        $url = $helper($routeName, $routeParams);

        if($absolute)
        {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'];
            return $protocol . rtrim($host, '/') . '/' . $url;
        }
        else
        {
            return $url;
        }
    }

    /**
     * @param IPageRepository $pageRepository
     * @return AbstractLink
     */
    protected function createLink(IPageRepository $pageRepository)
    {
        $link = null;
        $linkType = $this->getPostParameter('linkType');
        $linkPage = $this->getPostParameter('linkPage');
        $linkUrl = $this->getPostParameter('linkUrl');

        switch($linkType)
        {
            case PageLink::TYPE:
                $link = new PageLink();
                $page = $pageRepository->findById($linkPage);
                $link->setPage($page);
                break;
            case ExternalLink::TYPE:
                $link = new ExternalLink();
                $link->setUrl($linkUrl);
                break;
        }

        return $link;
    }
}