<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ICarouselItemRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteItemAction extends AbstractAdminAction
{
    const ROUTE = '/carousel/deleteItem/{id:\d+}';

    /**
     * @var ICarouselItemRepository
     */
    private $carouselItemRepository;

    /**
     * @param ICarouselItemRepository $carouselItemRepository
     */
    public function __construct(ICarouselItemRepository $carouselItemRepository)
    {
        $this->carouselItemRepository = $carouselItemRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $carouselItem = $this->carouselItemRepository->findById($id);
        if(!$carouselItem)
        {
            throw new NotFoundException();
        }

        $this->carouselItemRepository->delete($carouselItem);

        $this->getFlash()->success('Carousel afdbeelding verwijderd');

        return $this->redirectTo(ViewAction::ROUTE, ['id'=>$carouselItem->getCarousel()->getId()]);
    }
}