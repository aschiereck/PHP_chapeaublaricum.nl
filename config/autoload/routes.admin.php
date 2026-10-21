<?php

$adminActions = [
    //Article
    \App\Action\Admin\Article\IndexAction::class,
    \App\Action\Admin\Article\EditAction::class,
    \App\Action\Admin\Article\DeleteAction::class,
    //Auth
    \App\Action\Admin\Auth\LoginAction::class,
    \App\Action\Admin\Auth\LogoutAction::class,
    //Branding
    \App\Action\Admin\Branding\CssAction::class,
    //Calendar
    \App\Action\Admin\Calendar\IndexAction::class,
    \App\Action\Admin\Calendar\EditAction::class,
    \App\Action\Admin\Calendar\DeleteAction::class,
    //Carousel
    \App\Action\Admin\Carousel\IndexAction::class,
    \App\Action\Admin\Carousel\CreateAction::class,
    \App\Action\Admin\Carousel\DeleteAction::class,
    \App\Action\Admin\Carousel\ViewAction::class,
    \App\Action\Admin\Carousel\EditItemAction::class,
    \App\Action\Admin\Carousel\DeleteItemAction::class,
    \App\Action\Admin\Carousel\SortAction::class,
    //Dashboard
    \App\Action\Admin\Dashboard\IndexAction::class,
    //Facebook
    \App\Action\Admin\Facebook\IndexAction::class,
    \App\Action\Admin\Facebook\FetchRequestTokenAction::class,
    \App\Action\Admin\Facebook\CallBackAction::class,
    \App\Action\Admin\Facebook\ClearAction::class,
    //Form
    \App\Action\Admin\Form\IndexAction::class,
    \App\Action\Admin\Form\CreateAction::class,
    \App\Action\Admin\Form\DeleteAction::class,
    \App\Action\Admin\Form\ViewAction::class,
    \App\Action\Admin\Form\EditFormControlAction::class,
    \App\Action\Admin\Form\DeleteFormControlAction::class,
    \App\Action\Admin\Form\SortControlsAction::class,
    //Google analytics
    \App\Action\Admin\GoogleAnalytics\IndexAction::class,
    \App\Action\Admin\GoogleAnalytics\GetCodeAction::class,
    \App\Action\Admin\GoogleAnalytics\CallBackAction::class,
    \App\Action\Admin\GoogleAnalytics\ClearAction::class,
    \App\Action\Admin\GoogleAnalytics\GraphAction::class,
    //LayoutTextBlock
    \App\Action\Admin\LayoutTextBlock\IndexAction::class,
    \App\Action\Admin\LayoutTextBlock\EditAction::class,
    \App\Action\Admin\LayoutTextBlock\DeleteAction::class,
    //Menu
    \App\Action\Admin\Menu\IndexAction::class,
    \App\Action\Admin\Menu\EditAction::class,
    \App\Action\Admin\Menu\DeleteAction::class,
    \App\Action\Admin\Menu\SortItemsAction::class,
    //Page
    \App\Action\Admin\Page\IndexAction::class,
    \App\Action\Admin\Page\CreateAction::class,
    \App\Action\Admin\Page\EditAction::class,
    \App\Action\Admin\Page\DeleteAction::class,
    //PageRows
    \App\Action\Admin\PageBlock\SliderAction::class,
    \App\Action\Admin\PageBlock\FormAction::class,
    \App\Action\Admin\PageBlock\RecentArticlesAction::class,
    \App\Action\Admin\PageBlock\NewsArchiveAction::class,
    \App\Action\Admin\PageBlock\PhotoAlbumAction::class,
    \App\Action\Admin\PageBlock\CarouselAction::class,
    \App\Action\Admin\PageBlock\TeamAction::class,
    \App\Action\Admin\PageBlock\CustomTemplateAction::class,
    \App\Action\Admin\PageBlock\ProjectListAction::class,
    \App\Action\Admin\PageBlock\CalendarAction::class,
    \App\Action\Admin\PageBlock\UpcomingEventsAction::class,
    //PhotoAlbums
    \App\Action\Admin\PhotoAlbum\IndexAction::class,
    \App\Action\Admin\PhotoAlbum\CreateAction::class,
    \App\Action\Admin\PhotoAlbum\EditAction::class,
    \App\Action\Admin\PhotoAlbum\DeleteAction::class,
    \App\Action\Admin\PhotoAlbum\UploadPhotosAction::class,
    //Profile
    \App\Action\Admin\Profile\IndexAction::class,
    //Project
    \App\Action\Admin\Project\IndexAction::class,
    \App\Action\Admin\Project\EditAction::class,
    \App\Action\Admin\Project\DeleteAction::class,
    \App\Action\Admin\Project\SortAction::class,
    //Richtext
    \App\Action\Admin\RichText\UploadImageAction::class,
    \App\Action\Admin\RichText\UploadAttachmentAction::class,
    //Sites
    \App\Action\Admin\Site\IndexAction::class,
    \App\Action\Admin\Site\EditAction::class,
    \App\Action\Admin\Site\DeleteAction::class,
    //Slider
    \App\Action\Admin\Slider\IndexAction::class,
    \App\Action\Admin\Slider\CreateAction::class,
    \App\Action\Admin\Slider\DeleteAction::class,
    \App\Action\Admin\Slider\ViewAction::class,
    \App\Action\Admin\Slider\EditSlideAction::class,
    \App\Action\Admin\Slider\DeleteSlideAction::class,
    \App\Action\Admin\Slider\SortSlidesAction::class,
    //Team
    \App\Action\Admin\Team\IndexAction::class,
    \App\Action\Admin\Team\EditAction::class,
    \App\Action\Admin\Team\DeleteAction::class,
    \App\Action\Admin\Team\SortAction::class,
    //Twitter
    \App\Action\Admin\Twitter\IndexAction::class,
    \App\Action\Admin\Twitter\FetchRequestTokenAction::class,
    \App\Action\Admin\Twitter\CallBackAction::class,
    \App\Action\Admin\Twitter\ClearAction::class,
    //User
    \App\Action\Admin\User\IndexAction::class,
    \App\Action\Admin\User\EditAction::class,
    \App\Action\Admin\User\DeleteAction::class,
];


$config = [
    'dependencies' => [
        'factories' => []
    ],
    'routes' => []
];

foreach($adminActions as $actionClass)
{
    $route =  [
        'name' => $actionClass::ROUTE,
        'path' => '/' . ADMIN_SUFFIX . '/{siteId:\d+}' . $actionClass::ROUTE,
        'middleware' => $actionClass,
    ];

    $config['routes'][] = $route;
    $config['dependencies']['factories'][$actionClass] = \App\ReflectionFactory::class;
}

return $config;