<?php

namespace App\Service\Facebook;

use App\Repository\ISiteSettingRepository;
use App\Session\ISessionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class FacebookServiceFactory implements FactoryInterface
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

        return new FacebookService(
            $container->get(ISessionManager::class),
            $container->get(ISiteSettingRepository::class),
            $config['facebook']['app_id'],
            $config['facebook']['app_secret'],
            $config['facebook']['callback_url']
        );
    }
}