<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ISliderRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/slider/delete/{id:\d+}';

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
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $slider = $this->_sliderRepository->findById($id);
        if(!$slider)
        {
            throw new NotFoundException();
        }

        $this->_sliderRepository->delete($slider);

        $this->getFlash()->success('Slider verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}