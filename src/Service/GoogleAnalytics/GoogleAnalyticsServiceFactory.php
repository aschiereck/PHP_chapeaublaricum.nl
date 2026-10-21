<?php

namespace App\Service\GoogleAnalytics;

use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class GoogleAnalyticsServiceFactory implements FactoryInterface
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

        return new GoogleAnalyticsService(
            $container->get(ISessionManager::class),
            $container->get(ISiteSettingRepository::class),
            $config['google']['analytics']['client_key'],
            $config['google']['analytics']['client_secret'],
            $config['google']['analytics']['callback_url']
        );
    }
}