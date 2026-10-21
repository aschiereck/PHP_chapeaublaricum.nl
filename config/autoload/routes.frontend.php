<?php

return [
    'dependencies' => [
        'factories' => [
            \App\Action\Frontend\Page\ViewAction::class => \App\ReflectionFactory::class,
            \App\Action\Frontend\Article\ViewAction::class => \App\ReflectionFactory::class,
            \App\Action\Frontend\Project\ViewAction::class => \App\ReflectionFactory::class,
            \App\Action\Frontend\Sitemap\IndexAction::class => \App\ReflectionFactory::class,
            ]
        ],
    'routes' => [
        [
            'path' => '/sitemap.xml',
            'middleware' => \App\Action\Frontend\Sitemap\IndexAction::class,
        ],
        [
            'path' => '/a/{articleSlug}',
            'middleware' => \App\Action\Frontend\Article\ViewAction::class,
        ],
        [
            'path' => '/p/{projectSlug}',
            'middleware' => \App\Action\Frontend\Project\ViewAction::class,
        ],
        [
            'path' => '[/[{pageSlug}]]',
            'middleware' => \App\Action\Frontend\Page\ViewAction::class,
        ]
    ],
];
