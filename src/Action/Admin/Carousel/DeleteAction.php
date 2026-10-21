<?php

namespace App\Action\Admin\Carousel;

use App\Action\Admin\AbstractAdminAction;
use App\Exception\NotFoundException;
use App\Repository\ICarouselRepository;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends AbstractAdminAction
{
    const ROUTE = '/carousel/delete/{id:\d+}';

    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;

    /**
     * IndexAction constructor.
     * @param ICarouselRepository $carouselRepository
     */
    public function __construct(ICarouselRepository $carouselRepository)
    {
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $id = $this->getRouteParameter('id');
        $carousel = $this->carouselRepository->findById($id);
        if(!$carousel)
        {
            throw new NotFoundException();
        }

        $this->carouselRepository->delete($carousel);

        $this->getFlash()->success('Carousel verwijderd');

        return $this->redirectTo(IndexAction::ROUTE);
    }
}