<?php

namespace App\Action\Admin\PageBlock;

use App\Model\PageBlock\PageBlockRecentArticles;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class RecentArticlesAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/recent-articles';

    /**
     * RecentArticlesAction constructor.
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
        $invalidFields = [];

        $pageBlock = new PageBlockRecentArticles();

        if($this->isPostRequest())
        {
            $articles = (int)$this->getPostParameter('numberOfArticles');
            $pageBlock->setNumberOfArticles($articles);
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