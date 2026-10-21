<?php

namespace App\View\Frontend\Model;

use App\Model\Article;
use App\Model\MenuItem;
use App\Model\Page;
use App\Model\Site;
use App\Session\FlashMessageHandler;

class ViewModel
{
    /**
     * @var FlashMessageHandler
     */
    public $flashMessageHandler;
    /**
     * @var string
     */
    public $headTitle;
    /**
     * @var string
     */
    public $metaDescription;
    /**
     * @var MenuItem[]
     */
    public $menuItems;
    /**
     * @var string
     */
    public $currentUrl;
    /**
     * @var bool
     */
    public $allowSeoIndex = true;
    /**
     * @var FormResult[]
     */
    public $formResults = [];
    /**
     * @var string
     */
    public $ogImageUrl;
    /**
     * @var Page
     */
    public $activePage;
    /**
     * @var Article
     */
    public $activeArticle;
    /**
     * @var Site
     */
    public $site;

    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        $isSsl = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $protocol = $isSsl ? 'https://' : 'http://';
        $url =  $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $escapedUrl= htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
        return $protocol. $escapedUrl;
    }
}