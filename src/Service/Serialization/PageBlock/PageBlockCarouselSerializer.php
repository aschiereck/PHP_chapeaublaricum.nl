<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockCarousel;
use App\Repository\ICarouselRepository;

class PageBlockCarouselSerializer implements IPageBlockSerializer
{
    /**
     * @var ICarouselRepository
     */
    private $carouselRepository;

    /**
     * @param ICarouselRepository $carouselRepository
     */
    public function __construct(ICarouselRepository $carouselRepository)
    {
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @param PageBlockCarousel $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'carouselId' => $pageBlock->getCarousel()->getId()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockCarousel $pageBlock
     * @return PageBlockCarousel $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockCarousel();

        $carouselId = (int)$values['carouselId'];
        $carousel = $this->carouselRepository->findById($carouselId);

        $pageBlock->setCarousel($carousel);

        return $pageBlock;
    }
}
