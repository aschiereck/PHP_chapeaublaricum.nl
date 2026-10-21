<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Model\Slider;
use App\Repository\ISliderRepository;
use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/slider/create';

    /**
     * @var ISliderRepository
     */
    private $_sliderRepository;

    /**
     * @param ISliderRepository $sliderRepository
     */
    public function __construct(ISliderRepository $sliderRepository)
    {
        $this->_sliderRepository = $sliderRepository;
    }

    /**
     * @return Response|array
     */
    protected function execute()
    {
        $invalidFields = [];
        $slider = new Slider();
        $slider->setSite($this->getSite());

        if($this->isPostRequest())
        {
            $slider->setName($this->getPostParameter('name'));

            if ($slider->isValid())
            {
                $this->_sliderRepository->save($slider);
                $this->getFlash()->success('Gegevens opgeslagen');
                return $this->redirectTo(ViewAction::ROUTE, ['id'=>$slider->getId()]);
            }
            else
            {
                $invalidFields = $slider->getValidationErrors();
            }
        }

        return [
            'slider' => $slider,
            'invalidFields' => $invalidFields
        ];
    }
}