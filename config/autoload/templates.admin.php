<?php

return [
    'dependencies' => [
        'factories' => [
            Zend\Expressive\Template\TemplateRendererInterface::class => Zend\Expressive\ZendView\ZendViewRendererFactory::class,
            Zend\View\HelperPluginManager::class => Zend\Expressive\ZendView\HelperPluginManagerFactory::class,
            \App\View\Admin\Helper\AdminUrlHelper::class => \App\View\Admin\Helper\AdminHelperFactory::class
        ]
    ],

    'templates' => [
        'map' => [
            'error::404' => 'templates/admin/error/404.phtml',
            'error::error' => 'templates/admin/error/error.phtml',
            'admin/login' => 'templates/admin/login.phtml',
            'admin/default' => 'templates/admin/default.phtml',
            'admin/empty' => 'templates/admin/empty.phtml',
        ],
        'paths' => [
            'admin'    => ['templates/admin']
        ],
    ],

    'view_helpers' => [
        'invokables'=>array(
            'form' => \App\View\Admin\Helper\FormHelper::class,
            'Form' => \App\View\Admin\Helper\FormHelper::class,
        ),
        'factories' => array(
            \App\View\Admin\Helper\AdminHelperFactory::URL_HELPER_SHORT => \App\View\Admin\Helper\AdminHelperFactory::class,
            \App\View\Admin\Helper\AdminHelperFactory::MENU_HELPER_SHORT => \App\View\Admin\Helper\AdminHelperFactory::class,
            \App\View\Admin\Helper\AdminHelperFactory::TRANSLATION_HELPER_SHORT => \App\View\Admin\Helper\AdminHelperFactory::class,
            \App\View\Admin\Helper\AdminHelperFactory::IMAGE_HELPER_SHORT => \App\View\Admin\Helper\AdminHelperFactory::class,
        )
    ],
];
