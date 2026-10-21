<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockUpcomingEvents;

class PageBlockUpcomingEventsSerializer implements IPageBlockSerializer
{
    /**
     * @param PageBlockUpcomingEvents $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'numberOfEvents' => $pageBlock->getNumberOfEvents()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockUpcomingEvents $pageBlock
     * @return PageBlockUpcomingEvents $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockUpcomingEvents();

        $numberOfEvents = (int)$values['numberOfEvents'];
        $pageBlock->setNumberOfEvents($numberOfEvents);

        return $pageBlock;
    }
}
