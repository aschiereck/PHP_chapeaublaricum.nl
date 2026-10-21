<?php

namespace App\View\Admin\Model;

use App\Model\Site;
use App\Model\User;
use App\Session\FlashMessageHandler;

class ViewModel
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var Menu
     */
    public $menu;
	/**
     * @var bool
     */
    public $showTitle = true;
    /**
     * @var FlashMessageHandler
     */
    public $flashMessageHandler;
    /**
     * @var User
     */
    public $currentUser;
    /**
     * @var string
     */
    public $siteUrl;
    /**
     * @var string
     */
    public $cmsLogoUrl;
    /**
     * @var string
     */
    public $cmsName;
    /**
     * @var string
     */
    public $currentUrl;
    /**
     * @var Site
     */
    public $site;
    /**
     * @var Site[]
     */
    public $sites;
}