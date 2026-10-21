<?php

namespace App\Action\Admin\Branding;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Service\Evolved\IApi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\TextResponse;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;
use Zend\View\Model\ViewModel;

class CssAction implements MiddlewareInterface
{
    const ROUTE = '/branding/css';
    /**
     * @var IApi
     */
    private $api;

    /**
     * CssAction constructor.
     * @param IApi $api
     */
    public function __construct(IApi $api)
    {
        $this->api = $api;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param null|callable $out
     * @return null|ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $out = null)
    {
        $layout = new ViewModel([
            'encoding' => 'utf-8',
        ]);
        $layout->setTemplate(AbstractAdminTemplateAction::EMPTY_LAYOUT);
        $data = [
            'layout' => $layout,
            'branding' => $this->api->getBranding()
        ];

        $content = $this->getTemplateRenderer($request)->render('admin::branding/css.phtml', $data);

        $response = new TextResponse($content);
        return $response->withHeader('Content-type', 'text/css');
    }

    /**
     * @param ServerRequestInterface $request
     * @return TemplateRendererInterface
     */
    protected function getTemplateRenderer(ServerRequestInterface $request)
    {
        return $request->getAttribute(TemplateRendererInterface::class);
    }
}