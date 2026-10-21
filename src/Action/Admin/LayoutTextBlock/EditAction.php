<?php

namespace App\Action\Admin\LayoutTextBlock;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Exception\UniqueConstraintViolationException;
use App\Model\LayoutTextBlock;
use App\Repository\ILayoutTextBlockRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/layoutTextBlock/edit/[/{id:\d+}]';

    /**
     * @var ILayoutTextBlockRepository
     */
    private $layoutTextBlockRepository;

    /**
     * @param ILayoutTextBlockRepository $layoutTextBlockRepository
     */
    public function __construct(ILayoutTextBlockRepository $layoutTextBlockRepository)
    {
        $this->layoutTextBlockRepository = $layoutTextBlockRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $invalidFields = [];

        if($id)
        {
            $layoutBlock = $this->layoutTextBlockRepository->findById($id);
            if(!$layoutBlock)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $layoutBlock = new LayoutTextBlock();
            $layoutBlock->setSite($this->getSite());
        }

        if($this->isPostRequest())
        {
            $layoutBlock->setName($this->getPostParameter('name'));
            $layoutBlock->setTag($this->getPostParameter('tag'));
            $layoutBlock->setContent($this->getPostParameter('content'));

            if ($layoutBlock->isValid())
            {
                try
                {
                    $this->layoutTextBlockRepository->save($layoutBlock);
                    $this->getFlash()->success('Gegevens opgeslagen');
                    return $this->redirectTo(IndexAction::ROUTE);
                }
                catch(UniqueConstraintViolationException $e)
                {
                    $invalidFields[$e->getFieldName()] = ['De waarde voor dit veld moet uniek zijn maar is al in gebruik door een andere site'];
                }
            }
            else
            {
                $invalidFields = $layoutBlock->getValidationErrors();
            }
        }

        return [
            'layoutBlock' => $layoutBlock,
            'invalidFields' => $invalidFields
        ];
    }
}