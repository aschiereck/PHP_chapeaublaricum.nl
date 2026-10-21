<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 26-10-2016
 * Time: 23:43
 */
namespace App\Repository;

use App\Model\Article;
use App\Model\Site;

interface IArticleRepository
{
    /**
     * @param Article $article
     */
    public function delete(Article $article);

    /**
     * @param Article $article
     */
    public function save(Article $article);

    /**
     * @param Site $site
     * @param int $pageNr
     * @param bool $publishedOnly
     * @return Paginator
     */
    public function getPaginator(Site $site, $pageNr = 1, $publishedOnly=true);

    /**
     * @return Article[]
     */
    public function getArticlesToPublish();

    /**
     * @param $id
     * @return Article
     */
    public function findById($id);

    /**
     * @param Site $site
     * @param string $url
     * @param bool $publishedOnly
     * @return Article
     */
    public function getArticleByUrl(Site $site, $url, $publishedOnly=true);

    /**
     * @param Site $site
     * @param int $limit
     * @return Article[]
     */
    public function getMostRecentPublishedArticles(Site $site, $limit = 3);
}