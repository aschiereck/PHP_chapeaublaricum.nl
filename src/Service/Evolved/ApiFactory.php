<?php

namespace App\Service\Evolved;

use App\Config\IEvolvedConfigProvider;
use App\Util\Http\IHttpClientFactory;
use App\Service\License\LicenseValidator;
use App\Session\ISessionManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ApiFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Api(
            $container->get(IHttpClientFactory::class),
            $container->get(ISessionManager::class),
            $container->get(LicenseValidator::class),
            $container->get(IEvolvedConfigProvider::class)
        );
    }
}