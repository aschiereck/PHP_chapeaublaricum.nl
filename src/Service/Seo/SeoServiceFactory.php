<?php

namespace App\Service\Seo;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class SeoServiceFactory implements FactoryInterface
{
    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        switch($requestedName)
        {
            case ISeoService::class:
                return new SeoService();
            case IPageCompletenessChecker::class:
                return new PageCompletenessChecker();
            case IArticleCompletenessChecker::class:
                return new ArticleCompletenessChecker();
            case IProjectCompletenessChecker::class:
                return new ProjectCompletenessChecker();
        }
        throw new ServiceNotFoundException();
    }
}