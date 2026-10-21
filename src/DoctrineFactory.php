<?php

namespace App;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DoctrineFactory implements FactoryInterface
{
    private static $entityManager = null;

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
        if(is_null(self::$entityManager))
        {
            $config = $container->get('config');
            $proxyDir =  $config['doctrine']['orm']['proxy_dir'];
            $proxyNamespace = $config['doctrine']['orm']['proxy_namespace'];
            $autoGenerateProxyClasses = $config['doctrine']['orm']['auto_generate_proxy_classes'];

            // Doctrine ORM
            $doctrine = new Configuration();
            $doctrine->setProxyDir($proxyDir);
            $doctrine->setProxyNamespace($proxyNamespace);
            $doctrine->setAutoGenerateProxyClasses($autoGenerateProxyClasses);
            $doctrine->setNamingStrategy(new UnderscoreNamingStrategy());

            // ORM mapping by Annotation
            AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
            $driver = new AnnotationDriver(
                new AnnotationReader(),
                [
                    'src/Model',
                ]
            );
            $doctrine->setMetadataDriverImpl($driver);

            // EntityManager
            self::$entityManager = EntityManager::create($config['database']['connection'], $doctrine);
        }
        return self::$entityManager;
    }
}