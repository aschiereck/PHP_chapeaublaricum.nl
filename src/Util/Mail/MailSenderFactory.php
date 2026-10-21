<?php

namespace App\Util\Mail;

use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailSenderFactory implements FactoryInterface
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
        if(!isset($config['smtp']))
        {
            throw new \Exception('Mail configuration not yet set');
        }

        return new MailSender(
            $container->get(LoggerInterface::class),
            $config['smtp']['host'],
            $config['smtp']['port'],
            $config['smtp']['protocol'],
            $config['smtp']['username'],
            $config['smtp']['password'],
            $config['smtp']['from'],
            $config['smtp']['fromName']
        );
    }
}