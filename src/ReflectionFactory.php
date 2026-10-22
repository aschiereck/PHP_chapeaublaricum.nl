<?php

namespace App;

use Interop\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class ReflectionFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $parameterTypes = $this->reflectConstructorParams($container, $requestedName);

        $parameters = [];
        foreach ($parameterTypes as $type) {
            $parameters[] = $container->get($type);
        }

        return new $requestedName(...$parameters);
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return array
     */
    private function reflectConstructorParams(ContainerInterface $container, $requestedName)
    {
        $reflectionClass = new ReflectionClass($requestedName);
        if (null === ($constructor = $reflectionClass->getConstructor())) {
            return [];
        }
        $reflectionParameters = $constructor->getParameters();
        if (empty($reflectionParameters)) {
            return [];
        }
        $parameters = array_map(
            $this->resolveParameterType($container, $requestedName),
            $reflectionParameters
        );
        return $parameters;
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return \Closure
     */
    private function resolveParameterType(ContainerInterface $container, $requestedName)
    {
        /**
         * @param ReflectionParameter $parameter
         * @return mixed
         * @throws ServiceNotFoundException If type-hinted parameter cannot be
         *   resolved to a service in the container.
         */
        return function (ReflectionParameter $parameter) use ($container, $requestedName) {
            $type = null;
            $reflectionType = $parameter->getType();
            if ($reflectionType instanceof \ReflectionNamedType && !$reflectionType->isBuiltin()) {
                $type = $reflectionType->getName();
            } elseif (method_exists($parameter, 'getClass')) {
                $class = $parameter->getClass();
                $type = $class ? $class->getName() : null;
            }

            if (! $type) {
                throw new ServiceNotFoundException(sprintf(
                    'Cannot create "%s"; parameter "%s" has no type hint.',
                    $requestedName,
                    $parameter->getName()
                ));
            }

            if (! $container->has($type)) {
                throw new ServiceNotFoundException(sprintf(
                    'Cannot create "%s"; unable to resolve parameter "%s" using type hint "%s"',
                    $requestedName,
                    $parameter->getName(),
                    $type
                ));
            }
            return $type;
        };
    }
}