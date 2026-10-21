<?php

namespace App\Service\Translation;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TranslationServiceFactory implements FactoryInterface
{
    private static $translationService;

    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        if(!self::$translationService)
        {
            self::$translationService = new TranslationService($config['locale']);
        }
        return self::$translationService;
    }
}