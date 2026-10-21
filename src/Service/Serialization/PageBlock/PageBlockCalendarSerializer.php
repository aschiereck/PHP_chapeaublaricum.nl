<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockCalendar;
use App\Repository\ICalendarRepository;

class PageBlockCalendarSerializer implements IPageBlockSerializer
{
    /**
     * @var ICalendarRepository
     */
    private $calendarRepository;

    /**
     * @param ICalendarRepository $calendarRepository
     */
    public function __construct(ICalendarRepository $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    /**
     * @param PageBlockCalendar $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'calendarId' => $pageBlock->getCalendar()->getId()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockCalendar $pageBlock
     * @return PageBlockCalendar $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockCalendar();

        $calendarId = (int)$values['calendarId'];
        $calendar = $this->calendarRepository->findById($calendarId);

        $pageBlock->setCalendar($calendar);

        return $pageBlock;
    }
}
