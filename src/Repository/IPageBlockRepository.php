<?php

namespace App\Repository;

use App\Model\PageBlock\AbstractPageBlock;

interface IPageBlockRepository
{
    /**
     * @param AbstractPageBlock $block
     */
    public function save(AbstractPageBlock $block);

    /**
     * @param AbstractPageBlock $block
     */
    public function delete(AbstractPageBlock $block);

    /**
     * @param AbstractPageBlock $block
     * @param $id
     * @return object
     */
    public function findById(AbstractPageBlock $block, $id);

    /**
     * @param $id
     * @return AbstractPageBlock
     */
    public function findAbstractPageBlockById($id);


    /**
     * @return int
     */
    public function getMaxPosition();
}