<?php

return [
    'doctrine' => [
        'orm'        => [
            'auto_generate_proxy_classes' => true,
            'proxy_dir'                   => 'data/cache/EntityProxy',
            'proxy_namespace'             => 'EntityProxy'
        ]
    ],
];