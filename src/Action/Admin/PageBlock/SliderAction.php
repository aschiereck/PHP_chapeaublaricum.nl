<?php

namespace App\Action\Admin\PageBlock;

use App\Exception\NotFoundException;
use App\Model\PageBlock\PageBlockSlider;
use App\Repository\ISliderRepository;
use App\Service\Serialization\IPageBlockSerializerService;
use Psr\Http\Message\ResponseInterface as Response;

class SliderAction extends AbstractPageBlockAction
{
    const ROUTE = '/page/block/slider';
    /**
     * @var ISliderRepository
     */
    private $sliderRepository;

    /**
     * SliderAction constructor.
     * @param ISliderRepository $sliderRepository
     * @param IPageBlockSerializerService $pageBlockSerializerService
     */
    public function __construct(
        ISliderRepository $sliderRepository,
        IPageBlockSerializerService $pageBlockSerializerService
    )
    {
        parent::__construct($pageBlockSerializerService);
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @return array|Response
     * @throws NotFoundException
     */
    protected function execute()
    {
        $invalidFields = [];
        $pageBlock = new PageBlockSlider();

        if($this->isPostRequest())
        {
            $sliderId = $this->getPostParameter('sliderId');
            $slider = $this->sliderRepository->findById($sliderId);
            if(!$slider)
            {
                throw new NotFoundException();
            }
            $pageBlock->setSlider($slider);
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
            'sliders' => $this->sliderRepository->findList($this->getSite())
        ];
    }
}