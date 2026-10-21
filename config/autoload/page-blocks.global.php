<?php

return [
    'page_blocks' => [
        [
            'name' => 'Foto slider',
            'route' => \App\Action\Admin\PageBlock\SliderAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockSlider::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::PHOTO_SLIDER,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockSlider::TEMPLATE,
            'renderContainer' => false,
        ],
        [
            'name' => 'Formulier',
            'route' => \App\Action\Admin\PageBlock\FormAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockForm::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::FORM,
            'defaultWidth' => 12,
            'minWidth' => 3,
            'template' =>  \App\Model\PageBlock\PageBlockForm::TEMPLATE
        ],
        [
            'name' => 'Recente nieuwsberichten',
            'route' => \App\Action\Admin\PageBlock\RecentArticlesAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockRecentArticles::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::ARTICLE,
            'defaultWidth' => 6,
            'minWidth' => 3,
            'template' =>  \App\Model\PageBlock\PageBlockRecentArticles::TEMPLATE
        ],
        [
            'name' => 'Nieuwsarchief',
            'route' => \App\Action\Admin\PageBlock\NewsArchiveAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockNewsArchive::class,
            'canEdit' => false,
            'tag' => \App\Model\Module::ARTICLE,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockNewsArchive::TEMPLATE,
        ],
        [
            'name' => 'Foto album',
            'route' => \App\Action\Admin\PageBlock\PhotoAlbumAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockPhotoAlbum::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::PHOTO_ALBUM,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockPhotoAlbum::TEMPLATE
        ],
        [
            'name' => 'Carousel',
            'route' => \App\Action\Admin\PageBlock\CarouselAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockCarousel::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::CAROUSEL,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockCarousel::TEMPLATE
        ],
        [
            'name' => 'Team',
            'route' => \App\Action\Admin\PageBlock\TeamAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockTeam::class,
            'canEdit' => false,
            'tag' => \App\Model\Module::TEAM,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockTeam::TEMPLATE
        ],
        [
            'name' => 'Producten overzicht',
            'route' => \App\Action\Admin\PageBlock\ProjectListAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockProjectList::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::PROJECT,
            'defaultWidth' => 12,
            'minWidth' => 12,
            'template' =>  \App\Model\PageBlock\PageBlockProjectList::TEMPLATE
        ],
        [
            'name' => 'Kalender',
            'route' => \App\Action\Admin\PageBlock\CalendarAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockCalendar::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::CALENDAR,
            'defaultWidth' => 6,
            'minWidth' => 6,
            'template' =>  \App\Model\PageBlock\PageBlockCalendar::TEMPLATE
        ],
        [
            'name' => 'Opkomende evenementen',
            'route' => \App\Action\Admin\PageBlock\UpcomingEventsAction::ROUTE,
            'model' => \App\Model\PageBlock\PageBlockUpcomingEvents::class,
            'canEdit' => true,
            'tag' => \App\Model\Module::CALENDAR,
            'defaultWidth' => 6,
            'minWidth' => 6,
            'template' =>  \App\Model\PageBlock\PageBlockUpcomingEvents::TEMPLATE
        ]
    ]
];