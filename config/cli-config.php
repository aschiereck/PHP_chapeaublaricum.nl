<?php

define('AREA', 'doctrine');

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'container.php';
$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);