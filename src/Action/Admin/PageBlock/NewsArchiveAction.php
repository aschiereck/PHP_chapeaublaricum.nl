<?php

namespace App\Action\Admin\PageBlock;

use App\Model\PageBlock\PageBlockNewsArchive;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class NewsArchiveAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/news-archive';

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
        $pageBlock = new PageBlockNewsArchive();
        return $this->createSuccessJson($pageBlock);
    }
}