<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\FormControl;
use App\Repository\IFormControlRepository;
use App\Repository\IFormRepository;
use Psr\Http\Message\ResponseInterface as Response;

class EditFormControlAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/form/editFormControl/{parentId:\d+}[/{id:\d+}]';

    /**
     * @var IFormRepository
     */
    private $formRepository;
    /**
     * @var IFormControlRepository
     */
    private $formControlRepository;

    /**
     * @param IFormRepository $formRepository
     * @param IFormControlRepository $formControlRepository
     */
    public function __construct(IFormRepository $formRepository, IFormControlRepository $formControlRepository)
    {
        $this->formRepository = $formRepository;
        $this->formControlRepository = $formControlRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $formId = $this->getRouteParameter('parentId');
        $form = $this->formRepository->findById($formId);
        if(!$form)
        {
            throw new NotFoundException();
        }
        $invalidFields = [];

        if($id)
        {
            $formControl = $this->formControlRepository->findById($id);
            if(!$formControl)
            {
                throw new NotFoundException();
            }
        }
        else
        {
            $formControl = new FormControl();
            $formControl->setForm($form);
            $formControl->setPosition($this->formControlRepository->getMaxPosition() + 1);
        }

        if($this->isPostRequest())
        {
            $this->setPostData($formControl);

            if ($formControl->isValid())
            {
                $this->formControlRepository->save($formControl);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$formId]);
            }
            else
            {
                $invalidFields =  $formControl->getValidationErrors();
            }
        }
        return [
            'form' => $form,
            'formControl' => $formControl,
            'invalidFields' => $invalidFields,
            'typeOptions'=>$formControl->getTypeList(),
        ];
    }

    /**
     * @param FormControl $formControl
     */
    private function setPostData(FormControl $formControl)
    {
        $formControl->setLabel($this->getPostParameter('label'));
        $formControl->setType($this->getPostParameter('type'));
        $formControl->setIsRequired($this->getPostParameter('isRequired', false));

        if (in_array($this->getPostParameter('type'), [FormControl::TYPE_LIST_MULTIPLE, FormControl::TYPE_LIST_SINGLE]))
        {
            $formControl->setListOptions($this->getPostParameter('listOptions'));
        }
        else
        {
            $formControl->setListOptions('');
        }
    }
}