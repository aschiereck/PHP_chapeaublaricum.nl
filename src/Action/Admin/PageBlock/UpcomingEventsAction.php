<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockUpcomingEvents;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;


class UpcomingEventsAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/upcoming-events';

    /**
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(
        IPageBlockSerializerService $pageBlockSerializerService
    )
    {
        parent::__construct($pageBlockSerializerService);
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $invalidFields = [];
        $pageBlock = new PageBlockUpcomingEvents();

        if($this->isPostRequest())
        {
            $numberOfEvents = (int)$this->getPostParameter('numberOfEvents');
            $pageBlock->setNumberOfEvents($numberOfEvents);
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
            'invalidFields' => $invalidFields
        ];
    }
}