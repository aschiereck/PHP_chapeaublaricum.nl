<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockForm;
use App\Repository\IFormRepository;

class PageBlockFormSerializer implements IPageBlockSerializer
{
    /**
     * @var IFormRepository
     */
    private $formRepository;

    /**
     * @param IFormRepository $formRepository
     */
    public function __construct(IFormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    /**
     * @param PageBlockForm $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'formId' => $pageBlock->getForm()->getId()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockForm $pageBlock
     * @return PageBlockForm $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockForm();

        $formId = (int)$values['formId'];
        $form = $this->formRepository->findById($formId);

        $pageBlock->setForm($form);

        return $pageBlock;
    }
}
