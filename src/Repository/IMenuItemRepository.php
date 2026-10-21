<?php
namespace App\Repository;

use App\Model\MenuItem;
use App\Model\Site;

interface IMenuItemRepository
{
    /**
     * @param MenuItem $menuItem
     */
    public function delete(MenuItem $menuItem);

    /**
     * @param MenuItem $menuItem
     */
    public function save(MenuItem $menuItem);

    /**
     * @return int
     */
    public function getMaxPosition();

    /**
     * @param $id
     * @return MenuItem
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return MenuItem[]
     */
    public function findRootNodes(Site $site);

    /**
     * @param Site $site
     * @param MenuItem $excludeItem
     * @return MenuItem[]
     */
    public function findRootNodesList(Site $site, MenuItem $excludeItem=null);
}