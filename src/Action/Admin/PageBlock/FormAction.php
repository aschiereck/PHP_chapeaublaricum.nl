<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockForm;
use App\Repository\IFormRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;


class FormAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/form';

    /**
     * @var IFormRepository
     */
    private $formRepository;

    /**
     * SliderAction constructor.
     * @param IFormRepository $formRepository
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(IFormRepository $formRepository, IPageBlockSerializerService $pageBlockSerializerService)
    {
        parent::__construct($pageBlockSerializerService);
        $this->formRepository = $formRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
         $invalidFields = [];

        $pageBlock = new PageBlockForm();

        if($this->isPostRequest())
        {
            $formId = $this->getPostParameter('formId');
            $form = $this->formRepository->findById($formId);
            if(!$form)
            {
                throw new NotFoundException();
            }

            $pageBlock->setForm($form);
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
            'forms' => $this->formRepository->findList($this->getSite())
        ];
    }
}