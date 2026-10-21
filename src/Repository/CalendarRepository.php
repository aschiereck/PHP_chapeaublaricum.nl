<?php

namespace App\Repository;

use App\Model\Calendar;
use App\Model\CalendarEvent;
use App\Model\Site;

class CalendarRepository extends AbstractRepository implements ICalendarRepository
{
    /**
     * @param Calendar $calendar
     */
    public function delete(Calendar $calendar)
    {
        $this->_em->remove($calendar);
        $this->_em->flush();
    }


    /**
     * @param Calendar $calendar
     */
    public function save(Calendar $calendar)
    {
        $this->_em->persist($calendar);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Calendar|object
     */
    public function findById($id)
    {
        return $this->_em->find(Calendar::class, $id);
    }

    /**
     * @param Site $site
     * @return array
     */
    public function findList(Site $site)
    {
        $Calendars = array();
        foreach($site->getCalendars() as $Calendar)
        {
            $Calendars[$Calendar->getId()] = $Calendar->getName();
        }
        return $Calendars;
    }

    /**
     * @param Site $site
     * @param int $amount
     * @return CalendarEvent[]
     */
    public function getUpcomingEvents(Site $site, int $amount)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('event')
            ->from(CalendarEvent::class, 'event')
            ->join(Calendar::class, 'calendar')
            ->where($qb->expr()->gte('event.dateTime', ':now'))
            ->andWhere($qb->expr()->gte('calendar.site', $site->getId()))
            ->orderBy('event.dateTime', 'asc')
            ->setParameter('now', date('Y-m-d H:i:s'))
            ->setMaxResults($amount);

        return $qb->getQuery()->getResult();
    }
}