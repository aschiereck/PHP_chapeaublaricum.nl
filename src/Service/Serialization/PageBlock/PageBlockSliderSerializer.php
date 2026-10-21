<?php

namespace App\Service\Serialization\PageBlock;

use App\Model\PageBlock\PageBlockSlider;
use App\Repository\ISliderRepository;

class PageBlockSliderSerializer implements IPageBlockSerializer
{
    /**
     * @var ISliderRepository
     */
    private $sliderRepository;

    /**
     * @param ISliderRepository $sliderRepository
     */
    public function __construct(ISliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @param PageBlockSlider $pageBlock
     * @return string
     */
    public function toArray($pageBlock)
    {
        return [
            'sliderId' => $pageBlock->getSlider()->getId()
        ];
    }

    /**
     * @param array $values
     * @param PageBlockSlider $pageBlock
     * @return PageBlockSlider $pageBlock
     */
    public function fromArray($values, $pageBlock)
    {
        $pageBlock = $pageBlock ? $pageBlock : new PageBlockSlider();

        $sliderId = (int)$values['sliderId'];
        $slider = $this->sliderRepository->findById($sliderId);

        $pageBlock->setSlider($slider);

        return $pageBlock;
    }
}
