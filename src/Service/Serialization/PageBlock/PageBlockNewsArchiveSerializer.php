<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockNewsArchive;

class PageBlockNewsArchiveSerializer implements IPageBlockSerializer
{
    /**
     * @param PageBlockNewsArchive $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [];
    }

    /**
     * @param array $values
     * @param PageBlockNewsArchive $pageBlock
     * @return PageBlockNewsArchive $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockNewsArchive();
        return $pageBlock;
    }
}
