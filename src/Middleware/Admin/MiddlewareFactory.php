<?php

namespace App\Middleware\Admin;

use App\Repository\ISiteRepository;
use App\Service\Evolved\IApi;
use App\Service\Translation\ITranslationService;
use App\Session\IFlashMessageHandler;
use App\View\Admin\Helper\AdminUrlHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class MiddlewareFactory implements FactoryInterface
{

    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        switch($requestedName)
        {
            case PrepareAdminLayoutMiddleware::class:
                return new PrepareAdminLayoutMiddleware(
                    $container->get(IApi::class),
                    $container->get(TemplateRendererInterface::class),
                    $container->get(IFlashMessageHandler::class),
                    $container->get(AdminUrlHelper::class),
                    $container->get(ITranslationService::class),
                    $container->get(ISiteRepository::class)
                );
            case SiteMiddleware::class:
                return new SiteMiddleware(
                    $container->get(ISiteRepository::class)
                );
        }

        throw new ServiceNotFoundException($requestedName);
    }
}
