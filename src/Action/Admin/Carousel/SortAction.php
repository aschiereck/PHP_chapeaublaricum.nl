<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminAction;
use App\Repository\ICarouselItemRepository;
use App\Repository\ICarouselRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Zend\Diactoros\Response\EmptyResponse;

class SortAction extends AbstractAdminAction
{
    const ROUTE = '/carousel/sort/{id:\d+}';
    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;
    /**
     * @var ICarouselItemRepository
     */
    private $carouselItemRepository;

    /**
     * SortAction constructor.
     * @param ICarouselRepository $carouselRepository
     * @param ICarouselItemRepository $carouselItemRepository
     */
    public function __construct(ICarouselRepository $carouselRepository, ICarouselItemRepository $carouselItemRepository)
    {
        $this->carouselRepository = $carouselRepository;
        $this->carouselItemRepository = $carouselItemRepository;
    }

    /**
     * @return array|Response
     * @throws \Exception
     */
    protected function execute()
    {
        $carouselId = $this->getRouteParameter('id');
        $newPositions = $this->getPostParameter('position');

        $slider = $this->carouselRepository->findById($carouselId);
        if(!$slider)
        {
            throw new \Exception('Unknown carousel');
        }

        $carouselItemIds = array_map('intval', explode(',', $newPositions));
        $counter = 1;
        foreach($carouselItemIds as $carouselItemId)
        {
            $slide = $this->carouselItemRepository->findById($carouselItemId);
            if($slide)
            {
                $slide->setPosition($counter);
                $this->carouselItemRepository->save($slide);
            }
            else
            {
                throw new \Exception('Unknown item');
            }

            $counter++;
        }

        return new EmptyResponse();
    }
}