<?php
namespace App\Repository;

use App\Model\Site;

interface ISiteRepository
{
    /**
     * @param Site $site
     */
    public function delete(Site $site);

    /**
     * @param Site $site
     */
    public function save(Site $site);

    /**
     * @param $id
     * @return Site
     */
    public function findById($id);

    /**
     * @return Site[]
     */
    public function findAll();

    /**
     * @return Site
     */
    public function findFirstSite();

    /**
     * @param string $host
     * @return Site
     */
    public function finByUrl($host);
}