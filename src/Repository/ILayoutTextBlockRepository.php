<?php

namespace App\Repository;

use App\Model\LayoutTextBlock;
use App\Model\Site;

interface ILayoutTextBlockRepository
{
    /**
     * @param LayoutTextBlock $textBlock
     */
    public function delete(LayoutTextBlock $textBlock);

    /**
     * @param LayoutTextBlock $textBlock
     */
    public function save(LayoutTextBlock $textBlock);

    /**
     * @param $id
     * @return LayoutTextBlock
     */
    public function findById($id);

    /**
     * @param Site $site
     * @param $tag
     * @return LayoutTextBlock
     */
    public function findByTag(Site $site, string $tag);

    /**
     * @return LayoutTextBlock[]
     */
    public function findAll();
}