<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockCalendar;
use App\Repository\ICalendarRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;


class CalendarAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/calendar';
    /**
     * @var ICalendarRepository
     */
    private $calendarRepository;

    /**
     * SliderAction constructor.
     * @param ICalendarRepository $calendarRepository
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(
        ICalendarRepository $calendarRepository,
        IPageBlockSerializerService $pageBlockSerializerService
    )
    {
        parent::__construct($pageBlockSerializerService);
        $this->calendarRepository = $calendarRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $invalidFields = [];
        $pageBlock = new PageBlockCalendar();

        if($this->isPostRequest())
        {
            $calendarId = $this->getPostParameter('calendarId');
            $calendar = $this->calendarRepository->findById($calendarId);
            if(!$calendar)
            {
                throw new NotFoundException();
            }
            $pageBlock->setCalendar($calendar);
        }

        if($this->isPostRequest() && !$this->isPageBuilderRequest())
        {
            if ($pageBlock->isValid())
            {
                return $this->createSuccessJson($pageBlock);
            }
            else
            {
                return $this->createFailedJson($pageBlock->getValidationErrors());
            }
        }

        return [
            'pageBlock' => $pageBlock,
            'invalidFields' => $invalidFields,
            'calendars' => $this->calendarRepository->findList($this->getSite())
        ];
    }
}