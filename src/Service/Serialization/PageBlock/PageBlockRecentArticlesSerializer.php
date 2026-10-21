<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockRecentArticles;

class PageBlockRecentArticlesSerializer implements IPageBlockSerializer
{
    /**
     * @param PageBlockRecentArticles $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'numberOfArticles' => $pageBlock->getNumberOfArticles()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockRecentArticles $pageBlock
     * @return PageBlockRecentArticles $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockRecentArticles();

        $numberOfArticles = (int)$values['numberOfArticles'];
        $pageBlock->setNumberOfArticles($numberOfArticles);

        return $pageBlock;
    }
}
