<?php

namespace App\Service\Serialization;

use App\Repository\IPageBlockMetaRepository;
use App\Repository\IPageBlockRepository;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SerializerFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        switch ($requestedName)
        {
            case IPageBlockSerializerService::class:
                return new PageBlockSerializerService(
                    $container->get(IPageBlockSerializerFactory::class),
                    $container->get(IPageBlockMetaRepository::class),
                    $container->get(IPageBlockRepository::class)
                );
            case IPageBlockSerializerFactory::class:
                return new PageBlockSerializerFactory(
                    $container
                );
        }

        throw new ServiceNotFoundException();
    }
}