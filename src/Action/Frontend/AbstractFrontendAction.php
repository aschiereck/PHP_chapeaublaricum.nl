<?php

namespace App\Action\Frontend;

use App\Middleware\Frontend\SiteMiddleware;
use App\Model\Site;
use App\Session\IFlashMessageHandler;
use App\View\Frontend\Model\ViewModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

abstract class AbstractFrontendAction implements MiddlewareInterface
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
     * @param $name
     * @param null $default
     * @return mixed
     */
    protected function getRouteParameter($name, $default=null)
    {
        return $this->_request->getAttribute($name, $default);
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
     * @return Site
     */
    protected function getSite()
    {
        return $this->_request->getAttribute(SiteMiddleware::SITE);
    }
}