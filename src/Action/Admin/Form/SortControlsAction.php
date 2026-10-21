<?php

namespace App\Action\Admin\Form;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\IFormControlRepository;
use App\Repository\IFormRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortControlsAction extends AbstractAdminAction
{
    const ROUTE = '/form/sortControls/{id:\d+}';
    /**
     * @var IFormRepository
     */
    private $formRepository;
    /**
     * @var IFormControlRepository
     */
    private $formControlRepository;

    /**
     * SortSlidesAction constructor.
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
     * @throws \Exception
     */
    protected function execute()
    {
        $formId = $this->getRouteParameter('id');
        $newPositions = $this->getPostParameter('position');

        $form = $this->formRepository->findById($formId);
        if(!$form)
        {
            throw new \Exception('Unknown form');
        }

        $slideIds = array_map('intval', explode(',', $newPositions));
        $counter = 1;
        foreach($slideIds as $slideId)
        {
            $slide = $this->formControlRepository->findById($slideId);
            if($slide)
            {
                $slide->setPosition($counter);
                $this->formControlRepository->save($slide);
            }
            else
            {
                throw new \Exception('Unknown formcontrol');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}