<?php

namespace App\View\Admin\Model;

use App\Action\Admin\Dashboard\IndexAction;
use App\Model\Module;
use App\Model\Site;

class Menu
{
    const MENU_DASHBOARD = 'dashboard';
    const MENU_CONTENT_PAGE = 'page';
    const MENU_CONTENT_TEXT_BLOCKS = 'text_blocks';
    const MENU_CONTENT_MENU = 'menu';
    const MENU_CONTENT_ARTICLE = 'article';
    const MENU_MODULE_SLIDER = 'slider';
    const MENU_MODULE_PHOTO_ALBUMS = 'photo_albums';
    const MENU_MODULE_FORM = 'form';
    const MENU_MODULE_TEAM = 'team';
    const MENU_MODULE_CAROUSEL = 'carousel';
    const MENU_MODULE_CALENDAR= 'calendar';
    const MENU_MODULE_PROJECT = 'project';
    const MENU_SETTINGS_USER = 'user';
    const MENU_SETTINGS_ANALYTICS = 'analytics';
    const MENU_SETTINGS_FACEBOOK = 'facebook';
    const MENU_SETTINGS_TWITTER = 'twitter';
    const MENU_SETTINGS_SITES = 'sites';

    /**
     * @var Site
     */
    private $site;
    /**
     * @var string
     */
    private $_activeMenuItem;

    /**
     * Menu constructor.
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * @param string $activeMenuItem
     */
    public function setActive($activeMenuItem)
    {
        $this->_activeMenuItem = $activeMenuItem;
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        $menu = [];
        $menu[] = $this->createLink(
            self::MENU_DASHBOARD,
            'Dashboard',
            IndexAction::ROUTE,
            Module::DASHBOARD
        );

        $menu[] = $this->getContent();
        $menu[] = $this->getModulesMenu($this->site);
        $menu[] = $this->getSettingsMenu();

        return $menu;
    }

    /**
     * @return Site
     */
    public function getSite(): Site
    {
        return $this->site;
    }

    /**
     * @return MenuGroup
     */
    private function getContent()
    {
        $content = new MenuGroup('Website inhoud');
        
        $content->menuItems[] = $this->createLink(
            self::MENU_CONTENT_PAGE,
            'Pagina\'s',
            \App\Action\Admin\Page\IndexAction::ROUTE,
            Module::PAGE
        );

        
        $content->menuItems[] = $this->createLink(
            self::MENU_CONTENT_MENU,
           'Menu',
            \App\Action\Admin\Menu\IndexAction::ROUTE,
            Module::MENU
        );
        
        $content->menuItems[] = $this->createLink(
            self::MENU_CONTENT_TEXT_BLOCKS,
           'Widgets',
            \App\Action\Admin\LayoutTextBlock\IndexAction::ROUTE,
            Module::TEXT_BLOCK
        );
   
        return $content;
    }


    /**
     * @param Site $site
     * @return MenuGroup
     */
    private function getModulesMenu(Site $site)
    {
        $modules = new MenuGroup('Modules');

        $modules->menuItems[] = $this->createLink(
            self::MENU_CONTENT_ARTICLE,
            'Nieuwsberichten',
            \App\Action\Admin\Article\IndexAction::ROUTE,
            Module::ARTICLE,
            count($site->getArticles())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_SLIDER,
            'Foto sliders',
            \App\Action\Admin\Slider\IndexAction::ROUTE,
            Module::PHOTO_SLIDER,
            count($site->getSliders())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_FORM,
            'Formulieren',
            \App\Action\Admin\Form\IndexAction::ROUTE,
            Module::FORM,
            count($site->getForms())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_PHOTO_ALBUMS,
            'Foto albums',
            \App\Action\Admin\PhotoAlbum\IndexAction::ROUTE,
            Module::PHOTO_ALBUM,
            count($site->getPhotoAlbums())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_TEAM,
            'Teamleden',
            \App\Action\Admin\Team\IndexAction::ROUTE,
            Module::TEAM,
            count($site->getTeamMembers())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_PROJECT,
            'Producten',
            \App\Action\Admin\Project\IndexAction::ROUTE,
            Module::PROJECT,
            count($site->getProjects())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_CAROUSEL,
            'Carousel',
            \App\Action\Admin\Carousel\IndexAction::ROUTE,
            Module::CAROUSEL,
            count($site->getCarousel())
        );

        $modules->menuItems[] = $this->createLink(
            self::MENU_MODULE_CALENDAR,
            'Kalenders',
            \App\Action\Admin\Calendar\IndexAction::ROUTE,
            Module::CALENDAR,
            count($site->getCalendars())
        );

        return $modules;
    }

    /**
     * @return MenuGroup
     */
    private function getSettingsMenu()
    {
        $settings = new MenuGroup('Instellingen');

        $settings->menuItems[] = $this->createLink(
            self::MENU_SETTINGS_SITES,
            'Sites',
            \App\Action\Admin\Site\IndexAction::ROUTE,
            Module::SITE
        );

        $settings->menuItems[] = $this->createLink(
            self::MENU_SETTINGS_USER,
            'Cms gebruikers',
            \App\Action\Admin\User\IndexAction::ROUTE,
            Module::USER
        );

        $settings->menuItems[] = $this->createLink(
            self::MENU_SETTINGS_ANALYTICS,
            'Google analytics viewer',
            \App\Action\Admin\GoogleAnalytics\IndexAction::ROUTE,
            Module::GOOGLE_ANALYTICS
        );

        $settings->menuItems[] = $this->createLink(
            self::MENU_SETTINGS_FACEBOOK,
            'Facebook',
            \App\Action\Admin\Facebook\IndexAction::ROUTE,
            Module::FACEBOOK
        );

        $settings->menuItems[] = $this->createLink(
            self::MENU_SETTINGS_TWITTER,
            'Twitter',
            \App\Action\Admin\Twitter\IndexAction::ROUTE,
            Module::TWITTER
        );


        return $settings;
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $module
     * @param string $route
     * @param $count
     * @return MenuItem
     */
    private function createLink($name, $label, $route, $module, $count=0)
    {
        $item = new MenuItem(
            $name,
            $label,
            $route,
            $module
        );
        $item->count = $count;
        $item->isActive = ($name == $this->_activeMenuItem);

        return $item;
    }
}