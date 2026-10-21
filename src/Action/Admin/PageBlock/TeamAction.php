<?php

namespace App\Action\Admin\PageBlock;

use App\Model\PageBlock\PageBlockTeam;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class TeamAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/team';

    /**
     * NewsArchiveAction constructor.
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(IPageBlockSerializerService $pageBlockSerializerService)
    {
        parent::__construct($pageBlockSerializerService);
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $pageBlock = new PageBlockTeam();
        return $this->createSuccessJson($pageBlock);
    }
}