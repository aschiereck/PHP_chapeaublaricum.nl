<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockTeam;

class PageBlockTeamSerializer implements IPageBlockSerializer
{
    /**
     * @param PageBlockTeam $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [];
    }

    /**
     * @param array $values
     * @param PageBlockTeam $pageBlock
     * @return PageBlockTeam $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockTeam();
        return $pageBlock;
    }
}
