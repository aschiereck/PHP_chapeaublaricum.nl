<?php

namespace App\Action\Admin\Slider;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ISlideRepository;
use App\Repository\ISliderRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortSlidesAction extends AbstractAdminAction
{
    const ROUTE = '/slider/sortSlides/{id:\d+}';
    /**
     * @var ISliderRepository
     */
    private $sliderRepository;
    /**
     * @var ISlideRepository
     */
    private $slideRepository;

    /**
     * SortSlidesAction constructor.
     * @param ISliderRepository $sliderRepository
     * @param ISlideRepository $slideRepository
     */
    public function __construct(ISliderRepository $sliderRepository, ISlideRepository $slideRepository)
    {
        $this->sliderRepository = $sliderRepository;
        $this->slideRepository = $slideRepository;
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    protected function execute()
    {
        $sliderId = $this->getRouteParameter('id');
        $newPositions = $this->getPostParameter('position');

        $slider = $this->sliderRepository->findById($sliderId);
        if(!$slider)
        {
            throw new \Exception('Unknown slider');
        }

        $slideIds = array_map('intval', explode(',', $newPositions));
        $counter = 1;
        foreach($slideIds as $slideId)
        {
            $slide = $this->slideRepository->findById($slideId);
            if($slide)
            {
                $slide->setPosition($counter);
                $this->slideRepository->save($slide);
            }
            else
            {
                throw new \Exception('Unknown slide');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}