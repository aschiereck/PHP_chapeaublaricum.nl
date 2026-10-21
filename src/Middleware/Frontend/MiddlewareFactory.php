<?php

namespace App\Middleware\Frontend;

use App\Repository\IFormRepository;
use App\Repository\IMenuItemRepository;
use App\Repository\ISiteRepository;
use App\Session\IFlashMessageHandler;
use App\Util\Mail\IMailRenderer;
use App\Util\Mail\IMailSender;
use App\View\Frontend\Helper\LinkBuilder;
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
        $config = $container->get('config');

        switch($requestedName)
        {
            case FormValidatorMiddleWare::class:
                return new FormValidatorMiddleWare(
                    $container->get(IFormRepository::class),
                    $container->get(IMailSender::class),
                    $container->get(IMailRenderer::class),
                    $container->get(LinkBuilder::class),
                    isset($config['google']['recaptcha']['secret']) ? $config['google']['recaptcha']['secret'] : ''
                );
            case PrepareLayoutMiddleware::class:
                return new PrepareLayoutMiddleware(
                    $container->get(TemplateRendererInterface::class),
                    $container->get(IFlashMessageHandler::class),
                    $container->get(IMenuItemRepository::class)
                );
            case SiteMiddleware::class:
                return new SiteMiddleware(
                    $container->get(ISiteRepository::class)
                );
        }

        throw new ServiceNotFoundException($requestedName);
    }
}
