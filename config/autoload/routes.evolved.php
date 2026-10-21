<?php

return [
    'dependencies' => [
        'factories' => [
            \App\Action\Evolved\PingAction::class => \App\ReflectionFactory::class,
            \App\Action\Evolved\RemoteLoginAction::class => \App\ReflectionFactory::class,
            \App\Action\Evolved\InfoAction::class => \App\ReflectionFactory::class,
        ]
    ],
    'routes' => [
        [
            'path' => \App\Action\Evolved\PingAction::ROUTE,
            'middleware' => \App\Action\Evolved\PingAction::class,
        ],
        [
            'path' => \App\Action\Evolved\RemoteLoginAction::ROUTE,
            'middleware' => \App\Action\Evolved\RemoteLoginAction::class,
        ],        [
            'path' => \App\Action\Evolved\InfoAction::ROUTE,
            'middleware' => \App\Action\Evolved\InfoAction::class,
        ],
    ],
];
