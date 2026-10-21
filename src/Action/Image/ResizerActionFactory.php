<?php

namespace App\Action\Image;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ResizerActionFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        if(!isset($config['allowed-dimensions']['admin']))
        {
            throw new \Exception('dimensions not speficied for admin');
        }

        $allowedFrontendDimensions = isset($config['allowed-dimensions']['frontend']) ? $config['allowed-dimensions']['frontend'] : [];

        return new ResizerAction(
            $container->get(EntityManagerInterface::class),
            array_merge(
                $config['allowed-dimensions']['admin'],
                $allowedFrontendDimensions
            )
        );
    }
}