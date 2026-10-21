<?php

namespace App\Service\Install;

use App\Repository\ISiteRepository;
use App\Service\License\LicenseValidator;
use App\Util\Mail\IMailSender;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class InstallServiceFactory implements FactoryInterface
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
        return new InstallService(
            $container->get(EntityManagerInterface::class),
            $container->get(IMailSender::class),
            $container->get(LicenseValidator::class),
            $container->get(ISiteRepository::class)
        );
    }
}