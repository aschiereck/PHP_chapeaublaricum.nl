<?php
namespace App\Repository;

use App\Model\Calendar;
use App\Model\CalendarEvent;
use App\Model\Site;

interface ICalendarRepository
{
    /**
     * @param Site $site
     * @param int $amount
     * @return CalendarEvent[]
     */
    public function getUpcomingEvents(Site $site, int $amount);

    /**
     * @param Calendar $calendar
     */
    public function delete(Calendar $calendar);

    /**
     * @param Calendar $calendar
     */
    public function save(Calendar $calendar);

    /**
     * @param $id
     * @return Calendar|object
     */
    public function findById($id);

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site);
}