<?php

return [
    'dependencies' => [
        'factories' => [
            \App\Action\Image\ResizerAction::class => \App\Action\Image\ResizerActionFactory::class
        ],
    ],
    'routes' => [
        [
            'path' => '/media/cache/{type:[a-zA-Z]+}/{imageId:\d+}/{resizeType:[a-z]}-{width:\d+}-{height:\d+}-{filename}',
            'middleware' => \App\Action\Image\ResizerAction::class,
        ],
    ],
];