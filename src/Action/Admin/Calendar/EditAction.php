<?php

namespace App\Action\Admin\Calendar;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\Calendar;
use App\Model\CalendarEvent;
use App\Repository\ICalendarRepository;
use DateTime;


class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/calendar/[{id:\d+}]';

    /**
     * @var ICalendarRepository
     */
    private $calendarRepository;

    /**
     * IndexAction constructor.
     * @param ICalendarRepository $calendarRepository
     */
    public function __construct(ICalendarRepository $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    /**
     * @return array|\Zend\Diactoros\Response\RedirectResponse
     * @throws NotFoundException
     */
    public function execute()
    {
        $invalidFields = [];
        $id = $this->getRouteParameter('id');
        if($id)
        {
            $calendar = $this->calendarRepository->findById($id);
            if(!$calendar)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $calendar = new Calendar();
            $calendar->setSite($this->getSite());
        }

        if($this->isPostRequest())
        {
            $calendar->setName($this->getPostParameter('name'));

            $events = [];
            $rawEvents = json_decode($this->getPostParameter('events'), true);
            foreach($rawEvents as $rawEvent)
            {
                $event = new CalendarEvent();
                $event
                    ->setCalendar($calendar)
                    ->setName($rawEvent['name'])
                    ->setLocation($rawEvent['location'])
                    ->setDateTime(new DateTime($rawEvent['date'] . ' ' . $rawEvent['time'] . ':00'));

                $events[] = $event;
            }

            $calendar->setEvents($events);

            if($calendar->isValid())
            {
                $this->calendarRepository->save($calendar);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(IndexAction::ROUTE);
            }
            else
            {
                $invalidFields = $calendar->getValidationErrors();
            }

        }

        return [
            'invalidFields' => $invalidFields,
            'calendar' => $calendar
        ];
    }
}