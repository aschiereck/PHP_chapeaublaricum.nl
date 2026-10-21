<?php

namespace App\Middleware\Admin;

use App\Auth\AuthenticationMiddleware;
use App\Repository\ISiteRepository;
use App\Service\Evolved\IApi;
use App\Service\Translation\ITranslationService;
use App\Session\IFlashMessageHandler;
use App\View\Admin\Helper\AdminUrlHelper;
use App\View\Admin\Model\Menu;
use App\View\Admin\Model\ViewModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;
use Zend\Validator\AbstractValidator;

class PrepareAdminLayoutMiddleware implements MiddlewareInterface
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
     * @var AdminUrlHelper
     */
    private $_adminUrlHelper;
    /**
     * @var IApi
     */
    private $api;
    /**
     * @var ITranslationService
     */
    private $translationService;
    /**
     * @var ISiteRepository
     */
    private $siteRepository;

    /**
     * AbstractAdminAction constructor.
     * @param IApi $api
     * @param TemplateRendererInterface $templateRenderer
     * @param IFlashMessageHandler $flashMessageHandler
     * @param AdminUrlHelper $adminUrlHelper
     * @param ITranslationService $translationService
     * @param ISiteRepository $siteRepository
     */
    public function __construct(
        IApi $api,
        TemplateRendererInterface $templateRenderer,
        IFlashMessageHandler $flashMessageHandler,
        AdminUrlHelper $adminUrlHelper,
        ITranslationService $translationService,
        ISiteRepository $siteRepository
    )
    {
        $this->_templateRenderer = $templateRenderer;
        $this->_flashMessageHandler = $flashMessageHandler;
        $this->_adminUrlHelper = $adminUrlHelper;
        $this->api = $api;
        $this->translationService = $translationService;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param null|callable $out
     * @return null|Response
     */
    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $branding = $this->api->getBranding();

        $site = $request->getAttribute(SiteMiddleware::SITE);

        $this->_adminUrlHelper->setSite($site);

        $viewModel = new ViewModel();
        $viewModel->site = $site;
        $viewModel->flashMessageHandler = $this->_flashMessageHandler;
        $viewModel->currentUser = $request->getAttribute(AuthenticationMiddleware::USER_ATTRIBUTE);
        $viewModel->menu = new Menu($site);
        $viewModel->siteUrl = $request->getAttribute('productionUrl');
        $viewModel->cmsLogoUrl = $branding->getCmsLogoUrl();
        $viewModel->cmsName = $branding->getCmsName();
        $viewModel->currentUrl = $request->getUri()->__toString();
        $viewModel->sites = $this->siteRepository->findAll();

        AbstractValidator::setDefaultTranslator($this->translationService);

        $request = $request
            ->withAttribute(TemplateRendererInterface::class, $this->_templateRenderer)
            ->withAttribute(AdminUrlHelper::class, $this->_adminUrlHelper)
            ->withAttribute(ITranslationService::class, $this->translationService)
            ->withAttribute(ViewModel::class, $viewModel);

        return $out($request, $response);
    }
}
