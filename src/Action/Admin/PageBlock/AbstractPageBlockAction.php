<?php

namespace App\Action\Admin\PageBlock;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Service\Serialization\IPageBlockSerializerService;
use Zend\Diactoros\Response\HtmlResponse;

abstract class AbstractPageBlockAction extends AbstractAdminTemplateAction
{
    /**
     * @var IPageBlockSerializerService
     */
    private $pageBlockSerializerService;

    /**
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(IPageBlockSerializerService $pageBlockSerializerService)
    {
        $this->pageBlockSerializerService = $pageBlockSerializerService;
        $this->layoutName = AbstractAdminTemplateAction::EMPTY_LAYOUT;
    }

    /**
     * @param $pageBlock
     * @return HtmlResponse
     */
    protected function createSuccessJson($pageBlock)
    {
        $data = [
            'json' => [
                'success' => true,
                'blockId' => $this->getQueryParameter('blockId', false),
                'data' => $this->pageBlockSerializerService->toArray($pageBlock)
            ]
        ];

        return $this->createHtmlResponse(
            AbstractAdminTemplateAction::EMPTY_LAYOUT,
            'admin::pageblock/response-script.phtml',
            $data
        );
    }

    /**
     * @param [] $invalidFields
     * @return HtmlResponse
     */
    protected function createFailedJson($invalidFields)
    {
        $data = [
            'json' => [
                'success' => false,
                'blockId' => $this->getQueryParameter('blockId', false),
                'errors' => $invalidFields
            ]
        ];

        return $this->createHtmlResponse(
            AbstractAdminTemplateAction::EMPTY_LAYOUT,
            'admin::pageblock/response-script.phtml',
            $data
        );
    }

    /**
     * @return bool
     */
    protected function isPageBuilderRequest()
    {
        return (bool)$this->getPostParameter('pageBlockId', false);
    }
}
