<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockCarousel;
use App\Repository\ICarouselRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;


class CarouselAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/carousel';
    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;

    /**
     * SliderAction constructor.
     * @param ICarouselRepository $carouselRepository
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(
        ICarouselRepository $carouselRepository,
        IPageBlockSerializerService $pageBlockSerializerService
    )
    {
        parent::__construct($pageBlockSerializerService);
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $invalidFields = [];
        $pageBlock = new PageBlockCarousel();

        if($this->isPostRequest())
        {
            $carouselId = $this->getPostParameter('carouselId');
            $carousel = $this->carouselRepository->findById($carouselId);
            if(!$carousel)
            {
                throw new NotFoundException();
            }
            $pageBlock->setCarousel($carousel);
        }

        if($this->isPostRequest() && !$this->isPageBuilderRequest())
        {
            if ($pageBlock->isValid())
            {
                return $this->createSuccessJson($pageBlock);
            }
            else
            {
                return $this->createFailedJson($pageBlock->getValidationErrors());
            }
        }

        return [
            'pageBlock' => $pageBlock,
            'invalidFields' => $invalidFields,
            'carousels' => $this->carouselRepository->findList($this->getSite())
        ];
    }
}