<?php

return [
    'dependencies' => [
        'factories' => [
            Zend\Expressive\Template\TemplateRendererInterface::class => Zend\Expressive\ZendView\ZendViewRendererFactory::class,
            Zend\View\HelperPluginManager::class => Zend\Expressive\ZendView\HelperPluginManagerFactory::class,
        ]
    ],

    'templates' => [
        'map' => [
            'layout/default' => 'templates/frontend/default.phtml', //Used for error handler
            'frontend/default' => 'templates/frontend/default.phtml',
            'frontend/empty' => 'templates/general/empty.phtml',
            'frontend/404'      => 'templates/frontend/404.phtml',
            'frontend/mail/default'      => 'templates/frontend/mail/base.phtml',
        ],
        'paths' => [
            'frontend'    => ['templates/frontend'],
            'error'  => ['templates/frontend/error'],
        ],
    ],

    'view_helpers' => [
        'factories' => array(
            \App\View\Frontend\Helper\HelperFactory::BLOCK_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::LINK_BUILDER_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::FORM_RENDERER_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::CALENDAR_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::SITEMAP_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::META_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::MENU_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::ARTICLE_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::IMAGE_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
            \App\View\Frontend\Helper\HelperFactory::TRANSLATE_HELPER_SHORT  => \App\View\Frontend\Helper\HelperFactory::class,
        )
    ],
];
