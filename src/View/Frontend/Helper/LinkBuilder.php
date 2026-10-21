<?php

namespace App\View\Frontend\Helper;

use App\Model\Article;
use App\Model\Link\AbstractLink;
use App\Model\Link\ExternalLink;
use App\Model\Link\PageLink;
use App\Model\Page;
use App\Model\Project;
use Zend\View\Helper\AbstractHelper;

class LinkBuilder extends AbstractHelper
{
    /**
     * @var array
     */
    private $baseUrl;

    /**
     * LinkBuilder constructor.
     * @param string $baseUrl
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function home() : string
    {
        return $this->getBaseUrl();
    }

    /**
     * @param Page $page
     * @return string
     */
    public function page($page) : string
    {
        if($page)
        {
            return $this->getBaseUrl() . $page->getUrl();
        }
        else
        {
            return '';
        }
    }

    /**
     * @param bool $fullUrl
     * @return string
     */
    public function getBaseUrl($fullUrl = false) : string
    {
        if($fullUrl)
        {
            return $this->baseUrl;
        }
        else
        {
            return '/';
        }
    }

    /**
     * @param Article $article
     * @param bool $useFullUrl
     * @return string
     */
    public function article(Article $article, $useFullUrl = false) : string
    {
        return $this->getBaseUrl($useFullUrl). 'a/' . $article->getUrl();
    }

    /**
     * @param Project $project
     * @return string
     */
    public function project(Project $project) : string
    {
        return $this->getBaseUrl(). 'p/' . $project->getUrl();
    }

    /**
     * @param AbstractLink $link
     * @return null|string
     * @throws \Exception
     */
    public function link($link)
    {
        if(is_null($link))
        {
            return null;
        }

        switch(get_class($link))
        {
            case PageLink::class:
                /** @var PageLink $pageLink */
                $pageLink = $link;
                return $this->page($pageLink->getPage());
            case ExternalLink::class:
                /** @var ExternalLink $externalLink */
                $externalLink = $link;
                return $externalLink->getUrl();
            default:
                throw new \Exception('Unknown link type');
        }
    }
}