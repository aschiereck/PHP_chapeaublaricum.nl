<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IFormRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = 'form/delete/{id:\d+}';

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
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $form = $this->formRepository->findById($id);
        if(!$form)
        {
            throw new NotFoundException();
        }

        $this->formRepository->delete($form);

        $this->getFlash()->success('Formulier verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}