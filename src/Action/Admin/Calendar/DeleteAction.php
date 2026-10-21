<?php

namespace App\Action\Admin\Calendar;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ICalendarRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/calendar/delete/{id:\d+}';

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
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $carousel = $this->calendarRepository->findById($id);
        if(!$carousel)
        {
            throw new NotFoundException();
        }

        $this->calendarRepository->delete($carousel);

        $this->getFlash()->success('Kalender verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}