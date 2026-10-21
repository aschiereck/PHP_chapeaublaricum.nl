<?php

namespace App\Middleware;

use App\Service\License\LicenseValidator;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class MiddlewareFactory implements FactoryInterface
{

    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        switch($requestedName)
        {
            case LicenseValidationMiddleware::class:
                return new LicenseValidationMiddleware(
                    new LicenseValidator(
                        'data/license',
                        'data/public_key.pub'
                    )
                );
            case ErrorHandlerMiddleware::class:
                return new ErrorHandlerMiddleware();
        }

        throw new ServiceNotFoundException();
    }
}