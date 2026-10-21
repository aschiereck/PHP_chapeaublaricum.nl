<?php

namespace App\Middleware\Frontend;

use App\Repository\IMenuItemRepository;
use App\Session\IFlashMessageHandler;
use App\View\Frontend\Helper\HelperFactory;
use App\View\Frontend\Model\ViewModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;

class PrepareLayoutMiddleware implements MiddlewareInterface
{
    /**
     * @var TemplateRendererInterface
     */
    private $_templateRenderer;
    /**
     * @var IFlashMessageHandler
     */
    private $_flashMessageHandler;
    /**
     * @var IMenuItemRepository
     */
    private $menuItemRepository;


    /**
     * AbstractAdminAction constructor.
     * @param TemplateRendererInterface $templateRenderer
     * @param IFlashMessageHandler $flashMessageHandler
     * @param IMenuItemRepository $menuItemRepository
     */
    public function __construct(
        TemplateRendererInterface $templateRenderer,
        IFlashMessageHandler $flashMessageHandler,
        IMenuItemRepository $menuItemRepository
    )
    {
        $this->_templateRenderer = $templateRenderer;
        $this->_flashMessageHandler = $flashMessageHandler;
        $this->menuItemRepository = $menuItemRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $site = $request->getAttribute(SiteMiddleware::SITE);

        HelperFactory::setCurrentSite($site);

        $viewModel = new ViewModel();;
        $viewModel->flashMessageHandler = $this->_flashMessageHandler;
        $viewModel->menuItems = $this->menuItemRepository->findRootNodes($site);
        $viewModel->currentUrl = $request->getUri()->__toString();
        $viewModel->site = $site;

        $request = $request
            ->withAttribute(TemplateRendererInterface::class, $this->_templateRenderer)
            ->withAttribute(ViewModel::class, $viewModel);

        return $out($request, $response);
    }
}
