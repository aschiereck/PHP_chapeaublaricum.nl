<?php
namespace App\Repository;

use App\Model\Page;
use App\Model\Site;

interface IPageRepository
{
    /**
     * @param Page $article
     */
    public function delete(Page $article);

    /**
     * @param Page $article
     */
    public function save(Page $article);

    /**
     * @param $id
     * @return Page
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);


    /**
     * @param Site $site
     * @param string $url
     * @return Page
     */
    public function findPageByUrl(Site $site, $url);
}