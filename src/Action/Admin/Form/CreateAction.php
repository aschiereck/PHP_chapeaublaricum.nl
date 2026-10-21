<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Model\Form;
use App\Repository\IFormRepository;
use App\Repository\IPageRepository;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/form/create';

    /**
     * @var IFormRepository
     */
    private $formRepository;
    /**
     * @var IPageRepository
     */
    private $pageRepository;

    /**
     * @param IFormRepository $formRepository
     * @param IPageRepository $pageRepository
     */
    public function __construct(IFormRepository $formRepository, IPageRepository $pageRepository)
    {
        $this->formRepository = $formRepository;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $invalidFields = [];

        $form = new Form();
        $form->setSite($this->getSite());

        if($this->isPostRequest())
        {
            $form->setName($this->getPostParameter('name'));
            $form->setReceiver($this->getPostParameter('receiver'));
            $pageId = $this->getPostParameter('page');
            $form->setPage($this->pageRepository->findById($pageId));

            if ($form->isValid())
            {
                $this->formRepository->save($form);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$form->getId()]);
            }
            else
            {
                $invalidFields = $form->getValidationErrors();
            }
        }

        return [
            'form' => $form,
            'invalidFields' => $invalidFields,
            'pages'=>$this->pageRepository->findList($this->getSite())
        ];
    }
}