<?php

return [
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            'Zend\Expressive\Delegate\DefaultDelegate' => Zend\Expressive\Delegate\NotFoundDelegate::class,
            \App\Config\IEvolvedConfigProvider::class => \App\Config\AppConfig::class,
            \App\Config\IGoogleRecaptchaConfigProvider::class => \App\Config\AppConfig::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            Zend\Expressive\Helper\ServerUrlHelper::class => Zend\Expressive\Helper\ServerUrlHelper::class,
            \Zend\Expressive\Router\RouterInterface::class => \Zend\Expressive\Router\FastRouteRouter::class,
            //App
            \App\Util\Encryption\IPasswordEncryption::class => \App\Util\Encryption\PasswordEncryption::class,
            \App\Session\ISessionManager::class => \App\Session\SessionManager::class,
            \App\Session\IFlashMessageHandler::class => \App\Session\FlashMessageHandler::class,
            \App\Util\Http\IHttpClientFactory::class => \App\Util\Http\HttpClientFactory::class,
            \Zend\Permissions\Acl\AclInterface::class => App\Auth\Acl::class,
            \App\View\ImageHelper::class => \App\View\ImageHelper::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            Zend\Expressive\Application::class => Zend\Expressive\Container\ApplicationFactory::class,
            Zend\Expressive\Helper\ServerUrlMiddleware::class => Zend\Expressive\Helper\ServerUrlMiddlewareFactory::class,
            Zend\Expressive\Helper\UrlHelper::class => Zend\Expressive\Helper\UrlHelperFactory::class,
            Zend\Expressive\Helper\UrlHelperMiddleware::class => Zend\Expressive\Helper\UrlHelperMiddlewareFactory::class,
            Zend\Expressive\Delegate\NotFoundDelegate::class  => Zend\Expressive\Container\NotFoundDelegateFactory::class,

            //Middleware
            Zend\Stratigility\Middleware\ErrorHandler::class => Zend\Expressive\Container\ErrorHandlerFactory::class,
            Zend\Expressive\Middleware\ErrorResponseGenerator::class => Zend\Expressive\Container\ErrorResponseGeneratorFactory::class,
            Zend\Expressive\Middleware\NotFoundHandler::class => Zend\Expressive\Container\NotFoundHandlerFactory::class,
            \App\Middleware\Admin\PrepareAdminLayoutMiddleware::class => \App\Middleware\Admin\MiddlewareFactory::class,
            \App\Middleware\LicenseValidationMiddleware::class => \App\Middleware\MiddlewareFactory::class,
            \App\Auth\AuthenticationMiddleware::class => \App\Auth\AuthenticationMiddlewareFactory::class,
            \App\Auth\AuthorizationMiddleware::class => \App\Auth\AuthorizationMiddlewareFactory::class,
            \App\Middleware\ErrorHandlerMiddleware::class => \App\Middleware\MiddlewareFactory::class,
            \App\Middleware\Frontend\PrepareLayoutMiddleware::class => \App\Middleware\Frontend\MiddlewareFactory::class,
            \App\Middleware\Frontend\FormValidatorMiddleWare::class => \App\Middleware\Frontend\MiddlewareFactory::class,
            \App\Middleware\Admin\SiteMiddleware::class => \App\Middleware\Admin\MiddlewareFactory::class,
            \App\Middleware\Frontend\SiteMiddleware::class => \App\Middleware\Frontend\MiddlewareFactory::class,

            //App
            Doctrine\ORM\EntityManagerInterface::class  => App\DoctrineFactory::class,
            \App\View\Admin\Helper\AdminUrlHelper::class => \App\View\Admin\Helper\AdminHelperFactory::class,
            \App\Util\Mail\IMailRenderer::class => \App\Util\Mail\MailRendererFactory::class,
            \App\Util\Mail\IMailSender::class => \App\Util\Mail\MailSenderFactory::class,
            \App\View\Frontend\Helper\LinkBuilder::class => \App\View\Frontend\Helper\HelperFactory::class,

            //Services
            \App\Service\GoogleAnalytics\IGoogleAnalyticsService::class => \App\Service\GoogleAnalytics\GoogleAnalyticsServiceFactory::class,
            \App\Service\Facebook\IFacebookService::class => \App\Service\Facebook\FacebookServiceFactory::class,
            \App\Service\Twitter\ITwitterService::class => \App\Service\Twitter\TwitterServiceFactory::class,
            \App\Service\Seo\IPageCompletenessChecker::class => \App\Service\Seo\SeoServiceFactory::class,
            \App\Service\Seo\IArticleCompletenessChecker::class => \App\Service\Seo\SeoServiceFactory::class,
            \App\Service\Seo\IProjectCompletenessChecker::class => \App\Service\Seo\SeoServiceFactory::class,
            \App\Service\Seo\ISeoService::class => \App\Service\Seo\SeoServiceFactory::class,
            \App\Service\Serialization\IPageBlockSerializerService::class => \App\Service\Serialization\SerializerFactory::class,
            \App\Service\Serialization\IPageBlockSerializerFactory::class => \App\Service\Serialization\SerializerFactory::class,
            \App\Service\Evolved\IApi::class => \App\Service\Evolved\ApiFactory::class,
            \App\Service\License\LicenseValidator::class => \App\Service\License\LicenseValidatorFactory::class,
            \App\Service\Translation\ITranslationService::class => \App\Service\Translation\TranslationServiceFactory::class,
            \App\Service\Install\InstallService::class => \App\Service\Install\InstallServiceFactory::class,

            //Repo's
            \App\Repository\ISiteRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IUserRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\ISliderRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\ISlideRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IPageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ILayoutTextBlockRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\IAttachmentRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\IMenuItemRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IArticleRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IFormRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IFormControlRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IPageBlockRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IPageBlockMetaRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IPhotoAlbumRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\IPhotoRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ISiteSettingRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\IModuleRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ITeamMemberRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ICarouselRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ICarouselItemRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ICustomBlockTemplateRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ILinkRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\IProjectRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\ICalendarRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\ISlideImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\IRichTextImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\IArticleImageRepository::class  => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\IPhotoImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\IPageBlockImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\ITeamMemberImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\ICarouselImageRepository::class => \App\Repository\RepositoryFactory::class,
            \App\Repository\Image\IProjectImageRepository::class => \App\Repository\RepositoryFactory::class,
        ]
    ]
];