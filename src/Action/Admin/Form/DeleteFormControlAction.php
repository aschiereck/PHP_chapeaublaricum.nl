<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\IFormControlRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteFormControlAction extends AbstractAdminAction
{
    const ROUTE = '/form/deleteFormControl/{id:\d+}';

    /**
     * @var IFormControlRepository
     */
    private $formControlRepository;

    /**
     * @param IFormControlRepository $formControlRepository
     */
    public function __construct(IFormControlRepository $formControlRepository)
    {
        $this->formControlRepository = $formControlRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $formControl = $this->formControlRepository->findById($id);
        if(!$formControl)
        {
            throw new NotFoundException();
        }

        $this->formControlRepository->delete($formControl);

        $this->getFlash()->success('Formulier veld verwijderd');

        return $this->redirectTo(ViewAction::ROUTE, ['id'=>$formControl->getForm()->getId()]);
    }
}