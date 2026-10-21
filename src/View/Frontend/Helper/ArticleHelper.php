<?php

namespace App\View\Frontend\Helper;

use App\Model\Site;
use App\Repository\IArticleRepository;
use Zend\View\Helper\AbstractHelper;

class ArticleHelper extends AbstractHelper
{
    /**
     * @var IArticleRepository
     */
    private $articleRepository;
    /**
     * @var Site
     */
    private $currentSite;

    /**
     * TeamHelper constructor.
     * @param IArticleRepository $articleRepository
     * @param Site $currentSite
     */
    public function __construct(IArticleRepository $articleRepository, Site $currentSite)
    {
        $this->articleRepository = $articleRepository;
        $this->currentSite = $currentSite;
    }

    /**
     * @param int $limit
     * @return \App\Model\Article[]
     */
    public function getMostRecentArticles($limit)
    {
        return $this->articleRepository->getMostRecentPublishedArticles($this->currentSite, $limit);
    }

    /**
     * @return \App\Repository\Paginator
     */
    public function getPaginator()
    {
        $pageNr = (int) isset($_GET['page']) ? $_GET['page'] : 1;
        return $this->articleRepository->getPaginator($this->currentSite, $pageNr);
    }

}