<?php

namespace App\View\Frontend\Helper;

use App\Model\Site;
use App\Repository\ICalendarRepository;
use Zend\View\Helper\AbstractHelper;

class CalendarHelper extends AbstractHelper
{
    /**
     * @var ICalendarRepository
     */
    private $calendarRepository;
    /**
     * @var Site
     */
    private $currentSite;

    /**
     * CalendarHelper constructor.
     * @param ICalendarRepository $calendarRepository
     * @param Site $currentSite
     */
    public function __construct(ICalendarRepository $calendarRepository, Site $currentSite)
    {
        $this->calendarRepository = $calendarRepository;
        $this->currentSite = $currentSite;
    }

    /**
     * @param int $limit
     * @return \App\Model\CalendarEvent[]
     */
    public function getUpcomingEvents(int $limit)
    {
        return $this->calendarRepository->getUpcomingEvents($this->currentSite, $limit);
    }
}