<?php

namespace App\View\Admin\Helper;

use App\Repository\IModuleRepository;
use App\Service\Translation\ITranslationService;
use App\View\ImageHelper;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Router\RouterInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class AdminHelperFactory implements FactoryInterface
{
    const URL_HELPER_SHORT = 'adminUrl';
    const MENU_HELPER_SHORT = 'adminMenu';
    const TRANSLATION_HELPER_SHORT = '__';
    const IMAGE_HELPER_SHORT = 'image';

    /**
     * @var AdminUrlHelper
     */
    private static $adminUrlHelper;

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
            case AdminUrlHelper::class:
            case self::URL_HELPER_SHORT:
                if(!self::$adminUrlHelper)
                {
                    self::$adminUrlHelper = new AdminUrlHelper(
                        $container->get(RouterInterface::class)
                    );
                }
                return self::$adminUrlHelper;
            case AdminMenuHelper::class:
            case self::MENU_HELPER_SHORT:
                return new AdminMenuHelper(
                    $container->get(IModuleRepository::class),
                    $container->get(ITranslationService::class),
                    $container->get(AdminUrlHelper::class)
                );
            case TranslationHelper::class:
            case self::TRANSLATION_HELPER_SHORT:
                return new TranslationHelper(
                    $container->get(ITranslationService::class)
                );
            case self::IMAGE_HELPER_SHORT:
                return new ImageHelper();
        }

        throw new ServiceNotFoundException();
    }
}