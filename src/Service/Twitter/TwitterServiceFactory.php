<?php

namespace App\Service\Twitter;

use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TwitterServiceFactory implements FactoryInterface
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

        return new TwitterService(
            $container->get(ISessionManager::class),
            $container->get(ISiteSettingRepository::class),
            $config['twitter']['consumer_key'],
            $config['twitter']['consumer_secret'],
            $config['twitter']['callback_url']
        );
    }
}