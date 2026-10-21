<?php

namespace App\View\Frontend\Helper;

use App\Model\Site;
use App\Repository\IArticleRepository;
use App\Repository\ICalendarRepository;
use App\Repository\ILayoutTextBlockRepository;
use App\Service\License\LicenseValidator;
use App\Service\Translation\ITranslationService;
use App\Service\Translation\TranslationService;
use App\Session\ISessionManager;
use App\View\ImageHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class HelperFactory implements FactoryInterface
{
    /** @var  Site */
    private static $currentSite;

    const BLOCK_HELPER_SHORT = 'block';
    const TRANSLATE_HELPER_SHORT = 'translate';
    const FORM_RENDERER_HELPER_SHORT = 'form';
    const SITEMAP_HELPER_SHORT = 'sitemap';
    const LINK_BUILDER_HELPER_SHORT = 'link';
    const META_HELPER_SHORT = 'meta';
    const MENU_HELPER_SHORT = 'menu';
    const ARTICLE_HELPER_SHORT = 'article';
    const CALENDAR_HELPER_SHORT = 'calendar';
    const IMAGE_HELPER_SHORT = 'image';

    /**
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');

        switch($requestedName)
        {
            case self::IMAGE_HELPER_SHORT:
                return new ImageHelper();
            case self::SITEMAP_HELPER_SHORT:
                return new SitemapHelper();
            case self::LINK_BUILDER_HELPER_SHORT:
            case LinkBuilder::class:
                return $this->createLinkBuilder();
            case self::META_HELPER_SHORT:
                return new MetaHelper(
                    $this->createLinkBuilder()
                );
            case self::MENU_HELPER_SHORT:
                return new MenuHelper(
                    $this->createLinkBuilder()
                );
            case self::ARTICLE_HELPER_SHORT:
                return new ArticleHelper(
                    $container->get(IArticleRepository::class),
                    self::$currentSite
                );
            case self::CALENDAR_HELPER_SHORT:
                return new CalendarHelper(
                    $container->get(ICalendarRepository::class),
                    self::$currentSite
                );
            case self::BLOCK_HELPER_SHORT:
                return new LayoutBlockHelper(
                    $container->get(ILayoutTextBlockRepository::class),
                    self::$currentSite
                );
            case self::FORM_RENDERER_HELPER_SHORT:
                return new FormHelper(
                    $container->get(ISessionManager::class),
                    $container->get(ITranslationService::class),
                    isset($config['google']['recaptcha']['site_key']) ? $config['google']['recaptcha']['site_key'] : ''
                );
            case self::TRANSLATE_HELPER_SHORT:
                return new TranslationHelper(
                   new TranslationService(self::$currentSite->getLocale())
                );
        }

        throw new ServiceNotFoundException($requestedName);
    }

    /**
     * @return LinkBuilder
     */
    private function createLinkBuilder()
    {
        $validator = new LicenseValidator(
            'data/license',
            'data/public_key.pub'
        );
        $license = $validator->getLicense();
        $base = $license['productionUrl'] ?? '';

        return new LinkBuilder($base);
    }

    /**
     * @param Site $site
     */
    public static function setCurrentSite(Site $site)
    {
        self::$currentSite = $site;
    }
}