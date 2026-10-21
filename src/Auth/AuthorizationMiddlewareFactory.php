<?php

namespace App\Auth;

use Interop\Container\ContainerInterface;
use Zend\Permissions\Acl\AclInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthorizationMiddlewareFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AuthorizationMiddleware(
            $container->get(AclInterface::class)
        );
    }
}