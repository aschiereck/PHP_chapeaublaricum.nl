<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminTemplateAction;
use App\Exception\NotFoundException;
use App\Model\Slider;
use App\Repository\ISliderRepository;

class ViewAction extends AbstractAdminTemplateAction
{
    const ROUTE = '/slider/view/{id:\d+}';

    /**
     * @var ISliderRepository
     */
    private $_sliderRepository;

    /**
     * @var Slider
     */
    private $_slider;

    /**
     * @param ISliderRepository $sliderRepository
     */
    public function __construct(ISliderRepository $sliderRepository)
    {
        $this->_sliderRepository = $sliderRepository;
    }

    /**
     * @return array
     * @throws NotFoundException
     */
    public function execute()
    {
        $invalidFields = [];
        $id = $this->getRouteParameter('id');
        $this->_slider = $this->_sliderRepository->findById($id);

        if(!$this->_slider)
        {
            throw new NotFoundException();
        }

        if($this->isPostRequest())
        {
            $this->_slider->setName($this->getPostParameter('name'));
            if($this->_slider->isValid())
            {
                $this->_sliderRepository->save($this->_slider);
                $this->getFlash()->success('Gegevens opgeslagen');
            }
            else
            {
                $invalidFields = $this->_slider->getValidationErrors();
            }

        }

        return [
            'invalidFields' => $invalidFields,
            'slider' => $this->_slider
        ];
    }
}